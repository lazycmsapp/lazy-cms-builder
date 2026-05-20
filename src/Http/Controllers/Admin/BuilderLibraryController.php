<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BuilderLibraryController extends Controller
{
    const OPTION_KEY = 'lazy_builder_library';

    private function getLibrary(): array
    {
        $raw = get_cms_option(self::OPTION_KEY, null);
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) return $decoded;
        }
        return ['containers' => [], 'columns' => [], 'nested_columns' => [], 'elements' => []];
    }

    public function index()
    {
        return response()->json($this->getLibrary());
    }

    private function getPostCards(): array
    {
        $raw = get_cms_option('lazy_post_cards', null);
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) return $decoded;
        }
        return [];
    }

    public function page()
    {
        $library   = $this->getLibrary();
        $postCards = $this->getPostCards();
        return view('cms-dashboard::admin.lazy-builder.library', compact('library', 'postCards'));
    }

    public function savePostCard(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'config' => 'nullable|array',
        ]);

        $cards  = $this->getPostCards();
        $card   = [
            'id'         => (string) \Illuminate\Support\Str::uuid(),
            'name'       => $request->input('name'),
            'config'     => $request->input('config') ?? [],
            'created_at' => now()->format('Y-m-d H:i'),
        ];
        array_unshift($cards, $card);
        update_cms_option('lazy_post_cards', json_encode($cards));
        return response()->json(['success' => true, 'card' => $card]);
    }

    public function editPostCardBuilder(string $id)
    {
        $cards = $this->getPostCards();
        $postCard = collect($cards)->firstWhere('id', $id);
        if (!$postCard) abort(404);

        $customElements  = apply_lazy_filters('lazy_builder_elements', []);
        $bodyRaw         = get_cms_option('theme_typography_body');
        $headingRaw      = get_cms_option('theme_typography_h1');
        $bodyFont        = is_array($bodyRaw)    ? $bodyRaw    : json_decode((string)$bodyRaw,    true);
        $headingFont     = is_array($headingRaw) ? $headingRaw : json_decode((string)$headingRaw, true);
        $themeBodyFont   = $bodyFont['family']    ?? null;
        $themeHeadingFont = $headingFont['family'] ?? null;

        return view('cms-dashboard::admin.lazy-builder.post-card-builder', compact(
            'postCard', 'customElements', 'themeBodyFont', 'themeHeadingFont'
        ));
    }

    public function savePostCardLayout(Request $request, string $id)
    {
        $request->validate(['layout' => 'required|array']);
        $cards = $this->getPostCards();
        foreach ($cards as &$card) {
            if ($card['id'] === $id) {
                $card['config']['layout'] = $request->input('layout');
                break;
            }
        }
        update_cms_option('lazy_post_cards', json_encode($cards));
        return response()->json(['success' => true]);
    }

    public function updatePostCard(Request $request, string $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $cards = $this->getPostCards();
        foreach ($cards as &$card) {
            if ($card['id'] === $id) { $card['name'] = $request->input('name'); break; }
        }
        update_cms_option('lazy_post_cards', json_encode($cards));
        return response()->json(['success' => true]);
    }

    public function deletePostCard(string $id)
    {
        $cards = $this->getPostCards();
        $cards = array_values(array_filter($cards, fn($c) => $c['id'] !== $id));
        update_cms_option('lazy_post_cards', json_encode($cards));
        return response()->json(['success' => true]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'type' => 'required|in:containers,columns,nested_columns,elements',
            'name' => 'required|string|max:255',
            'data' => 'required|array',
        ]);

        $library = $this->getLibrary();
        $type    = $request->input('type');

        $item = [
            'id'         => (string) \Illuminate\Support\Str::uuid(),
            'name'       => $request->input('name'),
            'created_at' => now()->format('Y-m-d H:i'),
            'data'       => $request->input('data'),
        ];

        array_unshift($library[$type], $item);
        update_cms_option(self::OPTION_KEY, json_encode($library));

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function delete(string $type, string $id)
    {
        if (!in_array($type, ['containers', 'columns', 'nested_columns', 'elements'])) {
            return response()->json(['success' => false], 422);
        }

        $library = $this->getLibrary();
        $library[$type] = array_values(array_filter($library[$type], fn($i) => $i['id'] !== $id));
        update_cms_option(self::OPTION_KEY, json_encode($library));

        return response()->json(['success' => true]);
    }
}
