<?php
// FILE: app/Http/Controllers/Admin/CmsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsSetting;
use App\Models\News;
use App\Models\Committee;
use App\Models\CommitteeMember;
use App\Models\Promotion;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{
    // ── Homepage ──────────────────────────────────────────────────────────────
    public function homepage() {
        $settings = CmsSetting::pluck('value','key');
        return view('admin.cms.homepage', compact('settings'));
    }
    public function saveHomepage(Request $request) {
        $fields = ['hero_title','hero_tagline','hero_description','hero_btn_text','hero_btn_url',
                   'about_title','about_description','about_btn_text','about_btn_url'];
        foreach ($fields as $f) {
            CmsSetting::set($f, $request->$f);
        }
        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('cms','public');
            CmsSetting::set('hero_image', $path);
        }
        if ($request->hasFile('about_image')) {
            $path = $request->file('about_image')->store('cms','public');
            CmsSetting::set('about_image', $path);
        }
        return back()->with('success','Homepage content saved.');
    }

    // ── About ─────────────────────────────────────────────────────────────────
    public function about() {
        $settings = CmsSetting::pluck('value','key');
        return view('admin.cms.about', compact('settings'));
    }
    public function saveAbout(Request $request) {
        $fields = ['mission_title','mission_description','history_title','history_description'];
        foreach ($fields as $f) CmsSetting::set($f, $request->$f);
        if ($request->hasFile('mission_image')) {
            $path = $request->file('mission_image')->store('cms','public');
            CmsSetting::set('mission_image', $path);
        }
        // Timeline rows
        if ($request->has('years')) {
            $timeline = [];
            foreach ($request->years as $i => $year) {
                $timeline[] = ['year'=>$year,'heading'=>$request->headings[$i]??'','description'=>$request->descriptions[$i]??''];
            }
            CmsSetting::set('history_timeline', json_encode($timeline));
        }
        return back()->with('success','About content saved.');
    }

    // ── News ──────────────────────────────────────────────────────────────────
   // ── News ──────────────────────────────────────────────────────────────────
    public function news(Request $request) {
        $query = News::query();
        if ($request->search) $query->where('title','like',"%{$request->search}%");
        if ($request->type)   $query->where('type', $request->type);
        $news = $query->orderByDesc('created_at')->paginate(10);
        return view('admin.cms.news', compact('news'));
    }
    public function createNews() {
        return view('admin.cms.news_create');
    }
    public function storeNews(Request $request) {
        $request->validate(['title'=>'required']);
        $data = $request->except(['_token','image']);
        $data['published_at'] = now();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news','public');
        }
        News::create($data);
        return redirect()->route('admin.cms.news')->with('success','News added.');
    }
    public function editNews($id) {
        $item = News::findOrFail($id);
        return view('admin.cms.news_edit', compact('item'));
    }
    public function updateNews(Request $request, $id) {
        $item = News::findOrFail($id);
        $data = $request->except(['_token','_method','image']);
        if ($request->hasFile('image')) {
            if ($item->image) Storage::disk('public')->delete($item->image);
            $data['image'] = $request->file('image')->store('news','public');
        }
        $item->update($data);
        return redirect()->route('admin.cms.news')->with('success','News updated.');
    }
    public function deleteNews($id) {
        $item = News::findOrFail($id);
        if ($item->image) Storage::disk('public')->delete($item->image);
        $item->delete();
        return back()->with('success','News deleted.');
    }
    public function showNews($id)
{
    $item = News::findOrFail($id);
    return view('admin.cms.news_show', compact('item'));
}
    public function exportNews() {
        $news = News::orderByDesc('created_at')->get();
        $csv = "Title,Date Added,Type,Description\n";
        foreach ($news as $item) {
            $csv .= '"'.str_replace('"','""',$item->title).'",';
            $csv .= '"'.($item->created_at ? $item->created_at->format('d/m/Y') : '').'",';
            $csv .= '"'.str_replace('"','""',$item->type??'').'",';
            $csv .= '"'.str_replace('"','""',strip_tags($item->description??'')).'"'."\n";
        }
        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="news-export.csv"',
        ]);
    }

    // ── Verticals (Committees) ────────────────────────────────────────────────
    public function verticals(Request $request) {
        $committees = Committee::with('members')->orderByDesc('created_at')->paginate(10);
        return view('admin.cms.verticals', compact('committees'));
    }
    public function storeVertical(Request $request) {
        $request->validate(['title'=>'required']);
        $committee = Committee::create(['title'=>$request->title,'description'=>$request->description,'type'=>$request->type??'working']);
        if ($request->has('member_names')) {
            foreach ($request->member_names as $i => $name) {
                if ($name) CommitteeMember::create(['committee_id'=>$committee->id,'member_name'=>$name,'member_url'=>$request->member_urls[$i]??null,'sort_order'=>$i]);
            }
        }
        return back()->with('success','Committee added.');
    }
    public function editVertical($id) {
        $committee = Committee::with('members')->findOrFail($id);
        return view('admin.cms.verticals_edit', compact('committee'));
    }
    public function updateVertical(Request $request, $id) {
        $committee = Committee::findOrFail($id);
        $committee->update(['title'=>$request->title,'description'=>$request->description]);
        $committee->members()->delete();
        if ($request->has('member_names')) {
            foreach ($request->member_names as $i => $name) {
                if ($name) CommitteeMember::create(['committee_id'=>$id,'member_name'=>$name,'member_url'=>$request->member_urls[$i]??null,'sort_order'=>$i]);
            }
        }
        return redirect()->route('admin.cms.verticals')->with('success','Committee updated.');
    }
    public function deleteVertical($id) {
        Committee::findOrFail($id)->delete();
        return back()->with('success','Committee deleted.');
    }

    // ── Contact ───────────────────────────────────────────────────────────────
    public function contact() {
        $settings = CmsSetting::pluck('value','key');
        return view('admin.cms.contact', compact('settings'));
    }
    public function saveContact(Request $request) {
        $fields = ['contact_email','contact_number','location','google_map_link',
                   'bank_title','account_title','account_number','branch_number',
                   'social_twitter','social_instagram','social_facebook','social_linkedin','social_tiktok'];
        foreach ($fields as $f) CmsSetting::set($f, $request->$f);
        if ($request->hasFile('qr_code')) {
            $path = $request->file('qr_code')->store('cms','public');
            CmsSetting::set('qr_code', $path);
        }
        return back()->with('success','Contact settings saved.');
    }

    // ── Promotions ────────────────────────────────────────────────────────────
    public function promotions(Request $request) {
        $promos = Promotion::orderByDesc('created_at')->paginate(10);
        return view('admin.cms.promotions', compact('promos'));
    }
    public function storePromotion(Request $request) {
        $request->validate(['title'=>'required']);
        $data = $request->except(['_token','image']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('promotions','public');
        }
        Promotion::create($data);
        return back()->with('success','Promotion added.');
    }
    public function deletePromotion($id) {
        $promo = Promotion::findOrFail($id);
        if ($promo->image) Storage::disk('public')->delete($promo->image);
        $promo->delete();
        return back()->with('success','Promotion deleted.');
    }

    // ── FAQs ──────────────────────────────────────────────────────────────────
    public function faqs() {
        $faqs = Faq::orderBy('sort_order')->get();
        return view('admin.cms.faqs', compact('faqs'));
    }
    public function saveFaqs(Request $request) {
        Faq::truncate();
        if ($request->has('questions')) {
            foreach ($request->questions as $i => $q) {
                if ($q) Faq::create(['question'=>$q,'answer'=>$request->answers[$i]??'','sort_order'=>$i]);
            }
        }
        return back()->with('success','FAQs saved.');
    }

    // ── Footer & SEO ──────────────────────────────────────────────────────────
    public function footer() {
        $settings = CmsSetting::pluck('value','key');
        return view('admin.cms.footer', compact('settings'));
    }
    public function saveFooter(Request $request) {
        CmsSetting::set('footer_copyright', $request->copyright_text);
        return back()->with('success','Footer saved.');
    }
    public function seo() {
        $settings = CmsSetting::pluck('value','key');
        return view('admin.cms.seo', compact('settings'));
    }
    public function saveSeo(Request $request) {
        CmsSetting::set('seo_title', $request->meta_title);
        CmsSetting::set('seo_keywords', $request->keywords);
        CmsSetting::set('seo_description', $request->meta_description);
        return back()->with('success','SEO settings saved.');
    }
}
