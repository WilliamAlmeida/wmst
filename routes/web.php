<?php

use App\Http\Controllers\Admin\AgentShareLinkController;
use App\Http\Controllers\Admin\AiAgentController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostAgentController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BlogTagController;
use App\Http\Controllers\Public\AgentShareLinkAccessController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\CompanyController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProductsController;
use App\Http\Controllers\Public\ServicesController;
use App\Http\Controllers\Public\TrialSignupController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', [HomeController::class, 'index'])
    ->defaults('locale', 'pt_BR')
    ->name('home');

Route::get('/blog', [BlogController::class, 'index'])
    ->defaults('locale', 'pt_BR')
    ->name('blog.index');

Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->defaults('locale', 'pt_BR')
    ->name('blog.show');

Route::get('/produtos', [ProductsController::class, 'index'])
    ->defaults('locale', 'pt_BR')
    ->name('products.index');

Route::get('/produtos/{slug}', [ProductsController::class, 'show'])
    ->defaults('locale', 'pt_BR')
    ->name('products.show');

Route::get('/solucoes', [ServicesController::class, 'index'])
    ->defaults('locale', 'pt_BR')
    ->name('services.index');

Route::get('/solucoes/{slug}', [ServicesController::class, 'show'])
    ->defaults('locale', 'pt_BR')
    ->name('services.show');

Route::get('/sobre', [CompanyController::class, 'about'])
    ->defaults('locale', 'pt_BR')
    ->name('company.about');

Route::get('/cases', [CompanyController::class, 'cases'])
    ->defaults('locale', 'pt_BR')
    ->name('company.cases');

Route::get('/contato', [CompanyController::class, 'contact'])
    ->defaults('locale', 'pt_BR')
    ->name('company.contact');

Route::post('/trial-signups', [TrialSignupController::class, 'store'])
    ->defaults('locale', 'pt_BR')
    ->name('trial-signups.store');

Route::prefix('{locale}')
    ->whereIn('locale', ['es', 'en'])
    ->group(function (): void {
        Route::get('/', [HomeController::class, 'index'])->name('localized.home');
        Route::get('/blog', [BlogController::class, 'index'])->name('localized.blog.index');
        Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('localized.blog.show');
        Route::get('/produtos', [ProductsController::class, 'index'])->name('localized.products.index');
        Route::get('/produtos/{slug}', [ProductsController::class, 'show'])->name('localized.products.show');
        Route::get('/solucoes', [ServicesController::class, 'index'])->name('localized.services.index');
        Route::get('/solucoes/{slug}', [ServicesController::class, 'show'])->name('localized.services.show');
        Route::get('/sobre', [CompanyController::class, 'about'])->name('localized.company.about');
        Route::get('/cases', [CompanyController::class, 'cases'])->name('localized.company.cases');
        Route::get('/contato', [CompanyController::class, 'contact'])->name('localized.company.contact');
        Route::post('/trial-signups', [TrialSignupController::class, 'store'])->name('localized.trial-signups.store');
    });

Route::inertia('/legacy-welcome', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('legacy-welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::prefix('dashboard')->name('admin.')->group(function () {
        Route::get('blog-posts', [BlogPostController::class, 'index'])->name('blog-posts.index');
        Route::get('blog-posts/{blogPost}/edit', [BlogPostController::class, 'edit'])->name('blog-posts.edit');
        Route::post('blog-posts', [BlogPostController::class, 'store'])->name('blog-posts.store');
        Route::patch('blog-posts/{blogPost}', [BlogPostController::class, 'update'])->name('blog-posts.update');
        Route::delete('blog-posts/{blogPost}', [BlogPostController::class, 'destroy'])->name('blog-posts.destroy');

        Route::get('blog-post-agent', [BlogPostAgentController::class, 'index'])->name('blog-post-agent.index');
        Route::post('blog-post-agent/analyze', [BlogPostAgentController::class, 'analyze'])->name('blog-post-agent.analyze');
        Route::post('blog-post-agent/generate', [BlogPostAgentController::class, 'generate'])->name('blog-post-agent.generate');
        Route::post('blog-post-agent/improve/{blogPost}', [BlogPostAgentController::class, 'improve'])->name('blog-post-agent.improve');

        Route::post('blog-categories', [BlogCategoryController::class, 'store'])->name('blog-categories.store');
        Route::patch('blog-categories/{blogCategory}', [BlogCategoryController::class, 'update'])->name('blog-categories.update');
        Route::delete('blog-categories/{blogCategory}', [BlogCategoryController::class, 'destroy'])->name('blog-categories.destroy');

        Route::post('blog-tags', [BlogTagController::class, 'store'])->name('blog-tags.store');
        Route::patch('blog-tags/{blogTag}', [BlogTagController::class, 'update'])->name('blog-tags.update');
        Route::delete('blog-tags/{blogTag}', [BlogTagController::class, 'destroy'])->name('blog-tags.destroy');

        Route::get('ai-agents', [AiAgentController::class, 'index'])->name('ai-agents.index');
        Route::post('ai-agents', [AiAgentController::class, 'store'])->name('ai-agents.store');
        Route::patch('ai-agents/{aiAgent}', [AiAgentController::class, 'update'])->name('ai-agents.update');
        Route::delete('ai-agents/{aiAgent}', [AiAgentController::class, 'destroy'])->name('ai-agents.destroy');

        Route::post('ai-agents/{aiAgent}/share-links', [AgentShareLinkController::class, 'store'])->name('agent-share-links.store');
        Route::patch('share-links/{agentShareLink}/revoke', [AgentShareLinkController::class, 'revoke'])->name('agent-share-links.revoke');
    });
});

Route::get('share/{token}', [AgentShareLinkAccessController::class, 'show'])->name('share-links.show');
Route::post('share/{token}/events', [AgentShareLinkAccessController::class, 'storeEvent'])->name('share-links.events.store');

require __DIR__.'/settings.php';
