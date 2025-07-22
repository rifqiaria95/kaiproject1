<?php

namespace App\Http\Controllers\Mono;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Kategori;
use App\Models\Tag;
use App\Models\User;
use App\Http\Requests\NewsRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan Data news
        $news     = News::withoutTrashed()->with('user', 'categories', 'tags');
        $kategori = Kategori::all();
        $tags     = Tag::all();
        $users    = User::all();
        
        if ($request->ajax()) {
            return datatables()->of($news)
                ->addColumn('author', function ($data) {
                    return optional($data->user)->name ?? '-';
                })
                ->addColumn('category', function ($data) {
                    return $data->categories->pluck('name')->join(', ') ?: '-';
                })
                ->addColumn('tags', function ($data) {
                    return $data->tags->pluck('name')->join(', ') ?: '-';
                })
                ->addColumn('aksi', function ($data) {
                    $button = '';
                    return $button;
                })
                ->rawColumns(['author', 'category', 'tags', 'aksi'])
                ->addIndexColumn()
                ->toJson();
        }

        return view('news.index', compact(['news', 'kategori', 'tags', 'users']));
    }

    public function store(NewsRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            // Upload thumbnail jika ada
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/'), $filename);
                $validatedData['thumbnail'] = $filename;
            }

            // Set author_id, created_by berdasarkan user yang sedang login
            $validatedData['author_id'] = auth()->id();
            $validatedData['created_by'] = auth()->id();

            // Auto-fill published_at dan archived_at berdasarkan status
            $this->handleStatusTimestamps($validatedData);

            // Pisahkan category_id dan tags_id dari validatedData karena tidak ada di tabel news
            $categoryId = Arr::pull($validatedData, 'category_id');
            $tagsId = Arr::pull($validatedData, 'tags_id');

            // Create News (tanpa category_id dan tags_id)
            $news = News::create($validatedData);

            // Attach categories ke tabel pivot jika ada
            if ($categoryId) {
                $news->categories()->attach($categoryId);
            }

            // Attach tags ke tabel pivot jika ada
            if ($tagsId) {
                $news->tags()->attach($tagsId);
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Data news berhasil disimpan!',
                'data' => $news
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file yang sudah diupload jika ada error
            if (isset($filename) && File::exists(public_path('images/' . $filename))) {
                File::delete(public_path('images/' . $filename));
            }

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $news = News::with(['user', 'categories', 'tags'])->where('id', $id)->first();

            if (!$news) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data news tidak ditemukan'
                ], 404);
            }

            // Format data untuk frontend
            $newsData = $news->toArray();
            
            // Tambahkan category_id dan tags_id untuk form edit
            $newsData['category_id'] = $news->categories->pluck('id')->toArray();
            $newsData['tags_id'] = $news->tags->pluck('id')->toArray();

            return response()->json([
                'success' => true,
                'news' => $newsData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, NewsRequest $request)
    {
        try {
            DB::beginTransaction();

            $news = News::findOrFail($id);
            $validatedData = $request->validated();
            $oldThumbnail = $news->thumbnail;

            // Upload thumbnail baru jika ada
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/'), $filename);
                $validatedData['thumbnail'] = $filename;

                // Hapus thumbnail lama jika ada
                if ($oldThumbnail && File::exists(public_path('images/' . $oldThumbnail))) {
                    File::delete(public_path('images/' . $oldThumbnail));
                }
            }

            // Set updated_by berdasarkan user yang sedang login
            $validatedData['updated_by'] = auth()->id();

            // Auto-fill published_at dan archived_at berdasarkan status
            $this->handleStatusTimestamps($validatedData, $news);

            // Pisahkan category_id dan tags_id dari validatedData
            $categoryId = Arr::pull($validatedData, 'category_id');
            $tagsId = Arr::pull($validatedData, 'tags_id');

            // Update news (tanpa category_id dan tags_id)
            $news->update($validatedData);

            // Sync categories (ganti semua relasi yang ada)
            if ($categoryId !== null) {
                $news->categories()->sync($categoryId);
            }

            // Sync tags (ganti semua relasi yang ada)
            if ($tagsId !== null) {
                $news->tags()->sync($tagsId);
            }

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Data news berhasil diubah'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file yang sudah diupload jika ada error
            if (isset($filename) && File::exists(public_path('images/' . $filename))) {
                File::delete(public_path('images/' . $filename));
            }

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $news = News::where('id', $id)->first();

            if (!$news) {
                return response()->json([
                    'status' => 404,
                    'errors' => 'Data News Tidak Ditemukan'
                ]);
            }

            // Hapus thumbnail jika ada
            if ($news->thumbnail && File::exists(public_path('images/' . $news->thumbnail))) {
                File::delete(public_path('images/' . $news->thumbnail));
            }

            // Set deleted_by berdasarkan user yang sedang login
            $news->deleted_by = auth()->id();
            $news->save();

            // Hapus data (Soft Delete)
            $news->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Data News Berhasil Dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle automatic timestamps for published_at and archived_at based on status
     *
     * @param array &$validatedData
     * @param News|null $existingNews
     * @return void
     */
    private function handleStatusTimestamps(array &$validatedData, News $existingNews = null)
    {
        $status = $validatedData['status'] ?? null;
        $now = now();

        switch ($status) {
            case 'published':
                // Set published_at jika belum ada atau jika status berubah dari non-published ke published
                if (empty($validatedData['published_at'])) {
                    if (!$existingNews || $existingNews->status !== 'published') {
                        $validatedData['published_at'] = $now;
                    } elseif ($existingNews && $existingNews->published_at) {
                        // Pertahankan published_at yang sudah ada jika news sudah published sebelumnya
                        $validatedData['published_at'] = $existingNews->published_at;
                    }
                }
                // Clear archived_at ketika status menjadi published
                $validatedData['archived_at'] = null;
                break;

            case 'archived':
                // Set archived_at jika belum ada atau jika status berubah ke archived
                if (empty($validatedData['archived_at'])) {
                    if (!$existingNews || $existingNews->status !== 'archived') {
                        $validatedData['archived_at'] = $now;
                    } elseif ($existingNews && $existingNews->archived_at) {
                        // Pertahankan archived_at yang sudah ada jika news sudah archived sebelumnya
                        $validatedData['archived_at'] = $existingNews->archived_at;
                    }
                }
                break;

            case 'draft':
                // Clear both timestamps ketika status menjadi draft
                $validatedData['published_at'] = null;
                $validatedData['archived_at'] = null;
                break;
        }
    }
}
