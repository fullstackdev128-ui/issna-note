<?php 
  
 namespace Tests\Unit; 
  
 use Tests\TestCase; 
 use App\Services\NoteCalculatorService; 
 use App\Models\Note; 
 use App\Models\Etudiant; 
 use App\Models\ElementConstitutif; 
 use App\Models\UniteEnseignement; 
 use App\Models\AnneeAcademique;
 use App\Models\Specialite;
 use App\Models\Filiere;
 use App\Models\Campus;
 use App\Models\User;
 use Illuminate\Foundation\Testing\RefreshDatabase; 
  
 class NoteCalculatorServiceTest extends TestCase 
 { 
     use RefreshDatabase; 
  
     protected NoteCalculatorService $service; 
     protected User $user;

     protected function setUp(): void 
     { 
         parent::setUp(); 
         $this->service = app(NoteCalculatorService::class); 
         $this->user = User::create([
             'nom' => 'Admin',
             'email' => 'admin@test.com',
             'password' => bcrypt('password'),
             'role' => 'admin'
         ]);
     } 

     protected function createTestData()
     {
         $annee = AnneeAcademique::create(['libelle' => '2023-2024', 'active' => true, 'date_debut' => '2023-09-01', 'date_fin' => '2024-06-30']);
         $filiere = Filiere::create(['nom' => 'Test Filiere', 'code' => 'TF']);
         $campus = Campus::create(['nom' => 'Test Campus', 'code' => 'TC']);
         $specialite = Specialite::create(['nom' => 'Test Spec', 'code' => 'TS', 'duree_ans' => 3, 'type_diplome' => 'BTS', 'filiere_id' => $filiere->id]);
         $etudiant = Etudiant::create([
             'nom' => 'Test', 'prenoms' => 'Student', 'matricule' => 'STUD001', 
             'specialite_id' => $specialite->id, 'niveau_actuel' => 1, 'annee_acad_id' => $annee->id,
             'date_naissance' => '2000-01-01', 'genre' => 'M', 'campus_id' => $campus->id
         ]); 
         $ue = UniteEnseignement::create(['nom' => 'Test UE', 'code_ue' => 'UE001', 'specialite_id' => $specialite->id, 'semestre' => 1, 'niveau' => 1, 'type_ue' => 'Fondamentale']); 
         $ec = ElementConstitutif::create(['nom' => 'Test EC', 'code_ec' => 'EC001', 'ue_id' => $ue->id, 'credit' => 3]); 

         return [$annee, $specialite, $etudiant, $ue, $ec];
     }
  
     /** @test */ 
     public function grille_mgp_retourne_correct_pour_chaque_plage() 
     { 
         // A+ 
         $r = $this->service->getMGPetGrade(19); 
         $this->assertEquals('A+', $r['grade']); 
         $this->assertEquals(4.0, $r['mgp']); 
  
         // A 
         $r = $this->service->getMGPetGrade(17); 
         $this->assertEquals('A', $r['grade']); 
         $this->assertEquals(3.7, $r['mgp']); 
  
         // B+ 
         $r = $this->service->getMGPetGrade(15); 
         $this->assertEquals('B+', $r['grade']); 
         $this->assertEquals(3.3, $r['mgp']); 
  
         // B 
         $r = $this->service->getMGPetGrade(13); 
         $this->assertEquals('B', $r['grade']); 
         $this->assertEquals(3.0, $r['mgp']); 
  
         // C+ 
         $r = $this->service->getMGPetGrade(11.5); 
         $this->assertEquals('C+', $r['grade']); 
         $this->assertEquals(2.3, $r['mgp']); 
  
         // C 
         $r = $this->service->getMGPetGrade(10.5); 
         $this->assertEquals('C', $r['grade']); 
         $this->assertEquals(2.0, $r['mgp']); 
  
         // D 
         $r = $this->service->getMGPetGrade(9); 
         $this->assertEquals('D', $r['grade']); 
         $this->assertEquals(1.7, $r['mgp']); 
  
         // E 
         $r = $this->service->getMGPetGrade(7); 
         $this->assertEquals('E', $r['grade']); 
         $this->assertEquals(1.0, $r['mgp']); 
  
         // F 
         $r = $this->service->getMGPetGrade(4); 
         $this->assertEquals('F', $r['grade']); 
         $this->assertEquals(0.0, $r['mgp']); 
     } 
  
     /** @test */ 
     public function note_finale_ec_priorite_rp_sur_cc_sn() 
     { 
         list($annee, $specialite, $etudiant, $ue, $ec) = $this->createTestData();
  
         // Saisir CC=10, SN=12, RP=15 
         Note::create(['etudiant_id' => $etudiant->id, 'element_constitutif_id' => $ec->id, 
             'annee_acad_id' => $annee->id, 'semestre' => 1, 'type_examen' => 'CC', 
             'valeur' => 10, 'date_saisie' => now(), 'saisi_par' => $this->user->id]); 
         Note::create(['etudiant_id' => $etudiant->id, 'element_constitutif_id' => $ec->id, 
             'annee_acad_id' => $annee->id, 'semestre' => 1, 'type_examen' => 'SN', 
             'valeur' => 12, 'date_saisie' => now(), 'saisi_par' => $this->user->id]); 
         Note::create(['etudiant_id' => $etudiant->id, 'element_constitutif_id' => $ec->id, 
             'annee_acad_id' => $annee->id, 'semestre' => 1, 'type_examen' => 'RP', 
             'valeur' => 15, 'date_saisie' => now(), 'saisi_par' => $this->user->id]); 
  
         // RP doit primer 
         $note = $this->service->calculerNoteFinaleEC($etudiant->id, $ec->id, $annee->id, 1); 
         $this->assertEquals(15.0, $note); 
     } 
  
     /** @test */ 
     public function note_finale_ec_calcul_cc_sn_ponderation() 
     { 
         list($annee, $specialite, $etudiant, $ue, $ec) = $this->createTestData();
  
         // CC=10, SN=14 → (10*0.4) + (14*0.6) = 4 + 8.4 = 12.4 
         Note::create(['etudiant_id' => $etudiant->id, 'element_constitutif_id' => $ec->id, 
             'annee_acad_id' => $annee->id, 'semestre' => 1, 'type_examen' => 'CC', 
             'valeur' => 10, 'date_saisie' => now(), 'saisi_par' => $this->user->id]); 
         Note::create(['etudiant_id' => $etudiant->id, 'element_constitutif_id' => $ec->id, 
             'annee_acad_id' => $annee->id, 'semestre' => 1, 'type_examen' => 'SN', 
             'valeur' => 14, 'date_saisie' => now(), 'saisi_par' => $this->user->id]); 
  
         $note = $this->service->calculerNoteFinaleEC($etudiant->id, $ec->id, $annee->id, 1); 
         $this->assertEquals(12.4, $note); 
     } 
  
     /** @test */ 
     public function note_finale_ec_cc_seul_retourne_cc() 
     { 
         list($annee, $specialite, $etudiant, $ue, $ec) = $this->createTestData();
  
         Note::create(['etudiant_id' => $etudiant->id, 'element_constitutif_id' => $ec->id, 
             'annee_acad_id' => $annee->id, 'semestre' => 1, 'type_examen' => 'CC', 
             'valeur' => 13, 'date_saisie' => now(), 'saisi_par' => $this->user->id]); 
  
         $note = $this->service->calculerNoteFinaleEC($etudiant->id, $ec->id, $annee->id, 1); 
         $this->assertEquals(13.0, $note); 
     } 
  
     /** @test */ 
     public function note_finale_ec_sans_note_retourne_null() 
     { 
         list($annee, $specialite, $etudiant, $ue, $ec) = $this->createTestData();
  
         $note = $this->service->calculerNoteFinaleEC($etudiant->id, $ec->id, $annee->id, 1); 
         $this->assertNull($note); 
     } 
 } 
