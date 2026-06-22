# LearnFit — Route List

## Public Routes

| Method | URI | Name | Controller |
|--------|-----|------|-----------|
| GET | / | home | closure → welcome |
| GET | /tentang | about | closure → about |
| GET | /kontak | contact | closure → contact |

## Guest-only Routes (redirect to home if authenticated)

| Method | URI | Name | Controller |
|--------|-----|------|-----------|
| GET | /register | register | RegisterController@showForm |
| POST | /register | — | RegisterController@register |
| GET | /login | login | LoginController@showForm |
| POST | /login | — | LoginController@login |

## Auth Routes (semua authenticated user)

| Method | URI | Name | Controller |
|--------|-----|------|-----------|
| POST | /logout | logout | LoginController@logout |
| GET | /welcome | welcome | closure → auth.welcome-after |
| GET | /dashboard/profil | profil.show | ProfilController@show |
| PUT | /dashboard/profil | profil.update | ProfilController@update |

## Siswa-only Routes (auth + role:siswa)

| Method | URI | Name | Controller |
|--------|-----|------|-----------|
| GET | /dashboard/siswa | dashboard.siswa | closure |
| GET | /quiz | quiz | QuizController@show |
| POST | /quiz | quiz.submit | QuizController@submit |
| GET | /quiz/hasil | quiz.result | QuizController@result |
| GET | /quiz/ulang | quiz.retake | QuizController@retake |
| GET | /dashboard/catatan-belajar | catatan.index | JurnalBelajarController@index |
| POST | /dashboard/catatan-belajar | catatan.store | JurnalBelajarController@store |
| GET | /dashboard/catatan-belajar/{jurnal}/edit | catatan.edit | JurnalBelajarController@edit |
| PUT | /dashboard/catatan-belajar/{jurnal} | catatan.update | JurnalBelajarController@update |
| DELETE | /dashboard/catatan-belajar/{jurnal} | catatan.destroy | JurnalBelajarController@destroy |
| GET | /dashboard/sesi-belajar | sesi.index | SesiBelajarController@index |
| POST | /dashboard/sesi-belajar | sesi.store | SesiBelajarController@store |
| GET | /dashboard/sesi-belajar/{sesi}/start | sesi.start | SesiBelajarController@start |
| PATCH | /dashboard/sesi-belajar/{sesi}/complete | sesi.complete | SesiBelajarController@complete |
| DELETE | /dashboard/sesi-belajar/{sesi} | sesi.destroy | SesiBelajarController@destroy |
| POST | /dashboard/sesi-belajar/{sesi}/flashcards | flashcard.store | FlashcardController@store |
| PUT | /dashboard/flashcards/{flashcard} | flashcard.update | FlashcardController@update |
| DELETE | /dashboard/flashcards/{flashcard} | flashcard.destroy | FlashcardController@destroy |
| POST | /dashboard/sesi-belajar/{sesi}/notebook | notebook.store | NotebookController@store |
| DELETE | /dashboard/notebook/{entri} | notebook.destroy | NotebookController@destroy |

## Pengajar-only Routes (auth + role:pengajar)

| Method | URI | Name | Controller |
|--------|-----|------|-----------|
| GET | /dashboard/pengajar | dashboard.pengajar | closure |
| GET | /dashboard/kelas | dashboard.kelas | KelasController@index |
| POST | /dashboard/kelas | kelas.store | KelasController@store |
| GET | /dashboard/kelas/{kelas} | kelas.edit | KelasController@edit |
| PUT | /dashboard/kelas/{kelas} | kelas.update | KelasController@update |
| DELETE | /dashboard/kelas/{kelas} | kelas.destroy | KelasController@destroy |

## Middleware Summary

```
RoleMiddleware('role')
  handle($request, $next, ...$roles)
  - Cek $user->role ada di $roles
  - Jika tidak: redirect ke dashboard role asli + flash error
```
