<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use Livewire\Livewire;
use App\Livewire\Activity;
use App\Livewire\AuthorForm;
use App\Livewire\CategoryForm;
use App\Livewire\MetaDataForm;
use App\Livewire\RepositoryForm;
use App\Livewire\RepositoryList;
use App\Livewire\StudyProgramForm;
use App\Livewire\MetaDataCategoryForm;
use App\Livewire\MetaDataCategoryTable;


pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function categoryForm()
{
    return Livewire::test(CategoryForm::class);
}

function studyProgramForm()
{
    return Livewire::test(StudyProgramForm::class);
}

function authorForm()
{
    return Livewire::test(AuthorForm::class);
}

function repositoryList()
{
    return Livewire::test(RepositoryList::class);
}

function repositoryForm(array $param = [])
{
    return Livewire::test(RepositoryForm::class, $param);
}

function metaDataForm(array $param = [])
{
    return Livewire::test(MetaDataForm::class, $param);
}

function metaDataCategoryForm(array $param = [])
{
    return Livewire::test(MetaDataCategoryForm::class, $param);
}

function metaDataCategoryTable(array $param = [])
{
    return Livewire::test(MetaDataCategoryTable::class, $param);
}

function activity()
{
    return Livewire::test(Activity::class);
}
