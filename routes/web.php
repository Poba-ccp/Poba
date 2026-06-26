<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\AlumniController;
use App\Http\Controllers\Customer\EventController as CustomerEventController;
use App\Http\Controllers\Customer\GalleryController as CustomerGalleryController;
use App\Http\Controllers\Customer\NewsController;
use App\Http\Controllers\Customer\MemberController;
use App\Http\Controllers\Customer\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AlumniUserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Customer\ProfileController;

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Public / Customer ─────────────────────────────────────────────────────────
Route::get('/',                [HomeController::class, 'index'])->name('home');
Route::get('/about',           [HomeController::class, 'about'])->name('about');
Route::get('/promotions',      [HomeController::class, 'promotions'])->name('promotions');
Route::get('/contact',         [ContactController::class, 'index'])->name('contact');
Route::post('/contact',        [ContactController::class, 'send'])->name('contact.send');
Route::get('/updates',         [NewsController::class, 'index'])->name('news.index');
Route::get('/updates/{id}',    [NewsController::class, 'show'])->name('news.show');
Route::get('/events',          [CustomerEventController::class, 'index'])->name('events.index');
Route::get('/star-alumni',     [AlumniController::class, 'starAlumni'])->name('star.alumni');
Route::get('/gallery',         [CustomerGalleryController::class, 'index'])->name('gallery.index');
Route::get('/become-a-member', [MemberController::class, 'index'])->name('member.index');
Route::post('/become-a-member', [MemberController::class, 'store'])->name('member.store');

// Alumni-only routes
Route::middleware('alumni')->group(function () {
    Route::get('/alumni',      [AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/{id}', [AlumniController::class, 'show'])->name('alumni.show');

    Route::get('/gallery/{id}', [CustomerGalleryController::class, 'show'])->name('gallery.show');

    Route::post('/events/{id}/register', [CustomerEventController::class, 'register'])->name('events.register');
    Route::post('/events/{id}/cancel',   [CustomerEventController::class, 'cancel'])->name('events.cancel');

    // Profile
    Route::get('/profile',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

// ── Admin Panel ───────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ── Alumni Users ──────────────────────────────────────────────────────────
    Route::middleware('permission:alumni_users')->group(function () {
        Route::get('/alumni-users',                               [AlumniUserController::class, 'index'])->name('alumni.index');
        Route::get('/alumni-users/approvals',                     [AlumniUserController::class, 'approvals'])->name('alumni.approvals');
        Route::get('/alumni-users/export',                        [AlumniUserController::class, 'export'])->name('alumni.export');
        Route::get('/alumni-users/{id}',                          [AlumniUserController::class, 'show'])->name('alumni.show');
        Route::put('/alumni-users/{id}',                          [AlumniUserController::class, 'update'])->name('alumni.update');
        Route::post('/alumni-users/{id}/approve',                 [AlumniUserController::class, 'approve'])->name('alumni.approve');
        Route::post('/alumni-users/{id}/reject',                  [AlumniUserController::class, 'reject'])->name('alumni.reject');
        Route::post('/alumni-users/{id}/toggle-status',           [AlumniUserController::class, 'toggleStatus'])->name('alumni.toggleStatus');
        Route::post('/alumni-users/{id}/star',                    [AlumniUserController::class, 'markStar'])->name('alumni.star');
        Route::post('/alumni-users/{id}/update-star-description', [AlumniUserController::class, 'updateStarDescription'])->name('alumni.updateStarDescription');
        Route::post('/alumni-users/{id}/remove-star',             [AlumniUserController::class, 'removeStar'])->name('alumni.removeStar');
    });

    // ── Events ────────────────────────────────────────────────────────────────
    Route::middleware('permission:events')->group(function () {

        // IMPORTANT: static/named segments must come before wildcard {id} routes
        // to prevent Laravel matching "create" or "export" as an {id}.
        Route::get('/events/export',  [EventController::class, 'export'])->name('events.export');
        Route::get('/events/create',  [EventController::class, 'create'])->name('events.create');
        Route::post('/events',        [EventController::class, 'store'])->name('events.store');
        Route::get('/events',         [EventController::class, 'index'])->name('events.index');

        Route::get('/events/{id}/edit',         [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{id}',              [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{id}',           [EventController::class, 'destroy'])->name('events.destroy');

        // Participants list & export
        Route::get('/events/{id}/participants',        [EventController::class, 'participants'])->name('events.participants');
        Route::get('/events/{id}/participants/export', [EventController::class, 'exportParticipants'])->name('events.participants.export');

        // Participant actions — PATCH routes with two IDs
        Route::patch('/events/{eventId}/participants/{pId}/status',  [EventController::class, 'updateParticipantStatus'])->name('events.participants.status');
        Route::patch('/events/{eventId}/participants/{pId}/cancel',  [EventController::class, 'cancelParticipant'])->name('events.participants.cancel');
        Route::patch('/events/{eventId}/participants/{pId}/restore', [EventController::class, 'restoreParticipant'])->name('events.participants.restore');
    });

    // ── Gallery ───────────────────────────────────────────────────────────────
    Route::middleware('permission:gallery')->group(function () {
        Route::get('/gallery',                    [GalleryController::class, 'index'])->name('gallery.index');
        Route::get('/gallery/create',             [GalleryController::class, 'create'])->name('gallery.create');
        Route::post('/gallery',                   [GalleryController::class, 'store'])->name('gallery.store');
        Route::get('/gallery/{id}/edit',          [GalleryController::class, 'edit'])->name('gallery.edit');
        Route::put('/gallery/{id}',               [GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('/gallery/{id}',            [GalleryController::class, 'destroy'])->name('gallery.destroy');
        Route::get('/gallery/{id}/images',        [GalleryController::class, 'images'])->name('gallery.images');
        Route::post('/gallery/{id}/images',       [GalleryController::class, 'addImages'])->name('gallery.addImages');
        Route::delete('/gallery/images/{imgId}',  [GalleryController::class, 'deleteImage'])->name('gallery.deleteImage');
    });

    // ── CMS - Homepage ────────────────────────────────────────────────────────
    Route::middleware('permission:homepage')->group(function () {
        Route::get('/cms/homepage',  [CmsController::class, 'homepage'])->name('cms.homepage');
        Route::post('/cms/homepage', [CmsController::class, 'saveHomepage'])->name('cms.homepage.save');
    });

    // ── CMS - About ───────────────────────────────────────────────────────────
    Route::middleware('permission:about')->group(function () {
        Route::get('/cms/about',  [CmsController::class, 'about'])->name('cms.about');
        Route::post('/cms/about', [CmsController::class, 'saveAbout'])->name('cms.about.save');
    });

    // ── CMS - News ────────────────────────────────────────────────────────────
    Route::middleware('permission:news')->group(function () {
        Route::get('/cms/news',           [CmsController::class, 'news'])->name('cms.news');
        Route::get('/cms/news/export',    [CmsController::class, 'exportNews'])->name('cms.news.export');
        Route::get('/cms/news/create',    [CmsController::class, 'createNews'])->name('cms.news.create');
        Route::post('/cms/news',          [CmsController::class, 'storeNews'])->name('cms.news.store');
        Route::get('/cms/news/{id}',      [CmsController::class, 'showNews'])->name('cms.news.show');
        Route::get('/cms/news/{id}/edit', [CmsController::class, 'editNews'])->name('cms.news.edit');
        Route::put('/cms/news/{id}',      [CmsController::class, 'updateNews'])->name('cms.news.update');
        Route::delete('/cms/news/{id}',   [CmsController::class, 'deleteNews'])->name('cms.news.delete');
    });

    // ── CMS - Verticals ───────────────────────────────────────────────────────
    Route::middleware('permission:verticals')->group(function () {
        Route::get('/cms/verticals',           [CmsController::class, 'verticals'])->name('cms.verticals');
        Route::post('/cms/verticals',          [CmsController::class, 'storeVertical'])->name('cms.verticals.store');
        Route::get('/cms/verticals/{id}/edit', [CmsController::class, 'editVertical'])->name('cms.verticals.edit');
        Route::put('/cms/verticals/{id}',      [CmsController::class, 'updateVertical'])->name('cms.verticals.update');
        Route::delete('/cms/verticals/{id}',   [CmsController::class, 'deleteVertical'])->name('cms.verticals.delete');
    });

    // ── CMS - Contact ─────────────────────────────────────────────────────────
    Route::middleware('permission:contact')->group(function () {
        Route::get('/cms/contact',  [CmsController::class, 'contact'])->name('cms.contact');
        Route::post('/cms/contact', [CmsController::class, 'saveContact'])->name('cms.contact.save');
    });

    // ── CMS - Promotions ──────────────────────────────────────────────────────
    Route::middleware('permission:promotions')->group(function () {
        Route::get('/cms/promotions',         [CmsController::class, 'promotions'])->name('cms.promotions');
        Route::post('/cms/promotions',        [CmsController::class, 'storePromotion'])->name('cms.promotions.store');
        Route::delete('/cms/promotions/{id}', [CmsController::class, 'deletePromotion'])->name('cms.promotions.delete');
    });

    // ── CMS - FAQs ────────────────────────────────────────────────────────────
    Route::middleware('permission:faqs')->group(function () {
        Route::get('/cms/faqs',  [CmsController::class, 'faqs'])->name('cms.faqs');
        Route::post('/cms/faqs', [CmsController::class, 'saveFaqs'])->name('cms.faqs.save');
    });

    // ── CMS - Footer ─────────────────────────────────────────────────────────
    Route::middleware('permission:footer')->group(function () {
        Route::get('/cms/footer',  [CmsController::class, 'footer'])->name('cms.footer');
        Route::post('/cms/footer', [CmsController::class, 'saveFooter'])->name('cms.footer.save');
    });

    // ── CMS - SEO ─────────────────────────────────────────────────────────────
    Route::middleware('permission:seo')->group(function () {
        Route::get('/cms/seo',  [CmsController::class, 'seo'])->name('cms.seo');
        Route::post('/cms/seo', [CmsController::class, 'saveSeo'])->name('cms.seo.save');
    });

    // Theme Settings
    Route::middleware('permission:seo')->group(function () {
        Route::get('/theme',    [\App\Http\Controllers\Admin\ThemeController::class, 'index'])->name('theme.index');
        Route::put('/theme',    [\App\Http\Controllers\Admin\ThemeController::class, 'update'])->name('theme.update');
        Route::delete('/theme', [\App\Http\Controllers\Admin\ThemeController::class, 'reset'])->name('theme.reset');
    });

    // ── Admin Users — SuperAdmin only ─────────────────────────────────────────
    Route::middleware('superadmin')->group(function () {
        Route::get('/admin-users',           [AdminUserController::class, 'index'])->name('admin-users.index');
        Route::get('/admin-users/create',    [AdminUserController::class, 'create'])->name('admin-users.create');
        Route::post('/admin-users',          [AdminUserController::class, 'store'])->name('admin-users.store');
        Route::get('/admin-users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin-users.edit');
        Route::put('/admin-users/{id}',      [AdminUserController::class, 'update'])->name('admin-users.update');
        Route::delete('/admin-users/{id}',   [AdminUserController::class, 'destroy'])->name('admin-users.destroy');
    });
});
