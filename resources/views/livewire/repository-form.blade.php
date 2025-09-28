  <div>
      <section class="section">
          <div class="card">
              <div class="card-header">
                  <h4 class="card-title">Basic Inputs</h4>
              </div>
              <div class="card-body">
                  <div>
                      <div class="mb-4 row">
                          <div class="form-group">
                              <div>
                                  @error('title')
                                      <div class="alert alert-dark">
                                          <i class="bi bi-exclamation-triangle"></i>
                                          {{ $message }}
                                      </div>
                                  @enderror
                                  <label for="basicInput" class="form-label">Title</label>
                                  <input type="text" required class="form-control" id="basicInput" wire:model='title'
                                      placeholder="ex: Sistem Informasi Management Sekolah">
                              </div>

                              <div class="mt-4">
                                  @error('abstract')
                                      <div class="alert alert-dark">
                                          <i class="bi bi-exclamation-triangle"></i>
                                          {{ $message }}
                                      </div>
                                  @enderror
                                  <div class="mb-3 form-group">
                                      <label for="exampleFormControlTextarea1" class="form-label">Abstract</label>
                                      <textarea class="form-control" id="exampleFormControlTextarea1" wire:model='abstract' rows="3"></textarea>
                                  </div>
                              </div>

                              <div class="mt-4">
                                  @error('file_path')
                                      <div class="alert alert-dark">
                                          <i class="bi bi-exclamation-triangle"></i>
                                          {{ $message }}
                                      </div>
                                  @enderror
                                  <label for="formFile" class="form-label">
                                      File
                                  </label>
                                  <input class="form-control" wire:model='file_path' type="file" id="formFile"
                                      accept="application/pdf">
                              </div>

                              <div class="mt-4">
                                  @error('author_id')
                                      <div class="alert alert-dark">
                                          <i class="bi bi-exclamation-triangle"></i>
                                          {{ $message }}
                                      </div>
                                  @enderror
                                  <div class="input-group">
                                      <label class="input-group-text" for="inputGroupSelect01">
                                          Author
                                      </label>
                                      <select class="form-select" id="inputGroupSelect01" wire:model='author_id'>
                                          <option selected>Choose...</option>
                                          @foreach ($authors as $author)
                                              <option value="{{ $author->id }}">
                                                  {{ $author->name }}
                                              </option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>

                              <div class="mt-4">
                                  @error('type')
                                      <div class="alert alert-dark">
                                          <i class="bi bi-exclamation-triangle"></i>
                                          {{ $message }}
                                      </div>
                                  @enderror
                                  <div class="input-group">
                                      <label class="input-group-text" for="inputGroupSelect01">
                                          Type
                                      </label>
                                      <select class="form-select" id="inputGroupSelect01" wire:model='type'>
                                          <option selected>Choose...</option>
                                          <option value="thesis">
                                              Skripsi
                                          </option>
                                          <option value="final_project">
                                              Tugas Akhir
                                          </option>
                                          <option value="manual_book">
                                              Manual Book
                                          </option>
                                      </select>
                                  </div>
                              </div>

                              <div class="mt-4">
                                  @error('published_at')
                                      <div class="alert alert-dark">
                                          <i class="bi bi-exclamation-triangle"></i>
                                          {{ $message }}
                                      </div>
                                  @enderror
                                  <label for="published_at" class="form-label">Published At</label>
                                  <input type="date" class="mb-3 form-control" placeholder="Select date.."
                                      wire:model='published_at'>
                              </div>
                          </div>

                      </div>
                      <div class="gap-3 d-flex">
                          <button wire:click='create' wire:loading.attr='disabled' class="btn btn-primary">
                              Add
                              <span wire:loading>
                                  <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                              </span>
                          </button>
                          <button wire:click='resetInput' class="btn btn-warning">
                              Clear
                          </button>
                      </div>
                  </div>
              </div>
          </div>
      </section>
  </div>
