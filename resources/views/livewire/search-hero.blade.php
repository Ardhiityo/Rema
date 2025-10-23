  <div class="py-5 container-fluid feature bg-light" id="repositories">
      <div class="container py-5">
          <div class="py-4 mx-auto text-center wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
              <h4 class="text-primary">Solusi Akademik Digital</h4>
              <h1 class="display-5">Temukan inspirasi akademikmu.</h1>
              <p class="mb-0">Temukan inspirasi akademik dari ribuan karya mahasiswa. Dengan sistem pencarian cerdas
                  dan tampilan yang ramah pengguna, Rema hadir untuk mendukung perjalanan belajarmu.
              </p>
          </div>
          <div class="pb-3 row" id="search-hero">
              <div class="col-12">
                  {{-- Display Large Only --}}
                  <div class="mb-3 d-lg-flex d-none input-group input-group-lg">
                      <input type="text" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Title" wire:model.live.debounce.250ms='title'>
                      <input type="text" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Author" wire:model.live.debounce.250ms='author'>
                      <input type="number" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Year" wire:model.live.debounce.250ms='year'>
                      <select class="form-select" id="inputGroupSelect01" wire:model.live='category'>
                          @foreach ($categories as $category)
                              <option value="{{ $category->slug }}">{{ $category->name }}</option>
                          @endforeach
                      </select>
                      <button class="btn btn-primary d-flex align-items-center" wire:click='resetInput'
                          wire:loading.attr='disabled' wire:target='resetInput'>
                          <i class="bi bi-arrow-clockwise" wire:loading.class='d-none' wire:target='resetInput'></i>
                          <span wire:loading wire:target='resetInput'>
                              <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                          </span>
                      </button>
                  </div>
                  {{-- Display Medium Only --}}
                  <div class="mb-3 d-md-flex d-none d-lg-none input-group">
                      <input type="text" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Title" wire:model.live.debounce.250ms='title'>
                      <input type="text" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Author" wire:model.live.debounce.250ms='author'>
                      <input type="number" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Year" wire:model.live.debounce.250ms='year'>
                      <select class="form-select" id="inputGroupSelect01" wire:model.live='category'>
                          @foreach ($categories as $category)
                              <option value="{{ $category->slug }}">{{ $category->name }}</option>
                          @endforeach
                      </select>
                      <button class="btn btn-primary" wire:click='resetInput' wire:target='resetInput'>
                          <i class="bi bi-arrow-clockwise" wire:loading.class='d-none' wire:target='resetInput'></i>
                          <span wire:loading wire:target='resetInput'>
                              <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                          </span>
                      </button>
                  </div>
                  {{-- Display Small Only --}}
                  <div class="flex-wrap gap-2 mb-3 d-md-none d-flex d-lg-none">
                      <input type="text" class="form-control w-100" aria-label="Text input with dropdown button"
                          placeholder="Title" wire:model.live.debounce.250ms='title'>
                      <input type="text" class="form-control w-100" aria-label="Text input with dropdown button"
                          placeholder="Author" wire:model.live.debounce.250ms='author'>
                      <input type="number" class="form-control w-100" aria-label="Text input with dropdown button"
                          placeholder="Year" wire:model.live.debounce.250ms='year'>
                      <select class="form-select" id="inputGroupSelect01" wire:model.live='category'>
                          @foreach ($categories as $category)
                              <option value="{{ $category->slug }}">{{ $category->name }}</option>
                          @endforeach
                      </select>
                      <button class="btn btn-primary w-100" wire:click='resetInput' wire:target='resetInput'
                          wire:click.attr='disabled'>
                          <span wire:loading.class='d-none' wire:target='resetInput'>
                              <i class="bi bi-arrow-clockwise"></i> <small>Reset</small>
                          </span>
                          <span wire:loading wire:target='resetInput'>
                              <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                          </span>
                      </button>
                  </div>
              </div>
          </div>
          <div class="gap-4 row d-flex gap-md-0">
              @forelse ($repositories as $repository)
                  <div class="col-md-6 col-lg-6 col-xl-3 d-flex mt-md-4">
                      <a href="{{ route('repository.read', [
                          'category_slug' => $repository->category_slug,
                          'meta_data_slug' => $repository->metadata_slug,
                      ]) }}"
                          target="_blank" class="w-100">
                          <div class="p-4 pt-0 border feature-item h-100 d-flex flex-column justify-content-between">
                              <div>
                                  <div class="p-4 mb-4 feature-icon">
                                      <i class="fas fa-book-reader fa-3x"></i>
                                  </div>
                                  <div class="mb-4">
                                      <h4>{{ $repository->name }}</h4>
                                      <p>
                                          <small>
                                              {{ $repository->nim }} |
                                              {{ $repository->study_program }}
                                          </small>
                                      </p>
                                  </div>
                                  <p class="mb-4" title="{{ $repository->title }}">{{ $repository->title }}</p>
                              </div>
                              <div>
                                  <p>
                                      <small>
                                          <i class="fas fa-tags fa-xs me-1"></i>
                                          {{ $repository->category_name }}
                                      </small>
                                  </p>
                                  <p>
                                      <small>
                                          <i class="fas fa-chart-bar me-1"></i>
                                          {{ $repository->views }} Views
                                      </small>
                                  </p>
                                  <p>
                                      <small>
                                          <i class="me-1 fas fa-calendar-week"></i>
                                          Year of Graduation {{ $repository->year }}
                                      </small>
                                  </p>
                                  <span class="px-4 py-2 btn btn-primary rounded-pill">
                                      View <i class="fas fa-eye ms-2"></i>
                                  </span>
                              </div>
                          </div>
                      </a>
                  </div>
              @empty
                  <div class="col-12 d-flex justify-content-center">
                      <div class="mt-4">
                          <h5 class="text-muted">
                              Ups, no repositories available
                              <i class="far fa-frown-open"></i>
                          </h5>
                      </div>
                  </div>
              @endforelse
              <div class="py-5 col-12 d-flex justify-content-end">
                  {{ $repositories->Links() }}
              </div>
          </div>
      </div>
  </div>
