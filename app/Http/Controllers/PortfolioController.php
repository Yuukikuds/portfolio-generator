<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Support\PortfolioForm;
use App\Support\Tpl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    // ------------------------------------------------------------------ list (Manage page)

    public function index(): View
    {
        $portfolios = Portfolio::query()
            ->select(['id', 'full_name', 'email', 'selected_template', 'created_at', 'updated_at'])
            ->orderByDesc('updated_at')
            ->get();

        return view('portfolios.index', ['portfolios' => $portfolios]);
    }

    // ------------------------------------------------------------------ create / edit form

    public function create(): Response
    {
        return $this->renderForm(null, PortfolioForm::blank());
    }

    public function store(Request $request): Response|RedirectResponse
    {
        return $this->save($request, null);
    }

    public function edit(string $id): Response
    {
        $portfolio = Portfolio::findOrFail($id);
        session(['last_portfolio_id' => $portfolio->id]);

        return $this->renderForm($portfolio, PortfolioForm::fromModel($portfolio));
    }

    public function update(Request $request, string $id): Response|RedirectResponse
    {
        return $this->save($request, Portfolio::findOrFail($id));
    }

    private function renderForm(?Portfolio $portfolio, array $form, ?MessageBag $errors = null, int $status = 200): Response
    {
        if ($errors !== null) {
            // Make the errors visible to every view and component of this page.
            view()->share('errors', (new ViewErrorBag())->put('default', $errors));
        }

        $view = view('portfolios.form', [
            'portfolio' => $portfolio,
            'form' => $form,
            'isEdit' => $portfolio !== null,
            'actionUrl' => $portfolio ? route('portfolios.update', $portfolio->id) : route('portfolios.store'),
        ]);

        return response($view, $status);
    }

    /** Handles Save, Save & Continue, and the Add / Remove / photo buttons of the form. */
    private function save(Request $request, ?Portfolio $portfolio): Response|RedirectResponse
    {
        $form = PortfolioForm::fromRequest($request);
        $action = (string) $request->input('action', 'save');

        // A newly chosen photo replaces the one already kept in the hidden field.
        $pictureError = null;
        if ($request->hasFile('profile_picture_file')) {
            [$picture, $pictureError] = PortfolioForm::processUpload($request->file('profile_picture_file'));
            if ($picture !== null) {
                $form['profile_picture'] = $picture;
            }
        }

        // Add / Remove / photo buttons: show the form again, nothing is saved.
        if ($action !== 'save' && $action !== 'continue') {
            PortfolioForm::applyAction($form, $action);
            $errors = $pictureError !== null ? new MessageBag(['profile_picture' => [$pictureError]]) : null;

            return $this->renderForm($portfolio, $form, $errors);
        }

        $clean = PortfolioForm::clean($form);
        $validator = PortfolioForm::validator($clean);
        if ($pictureError !== null) {
            $validator->after(function ($v) use ($pictureError) {
                $v->errors()->add('profile_picture', $pictureError);
            });
        }

        if ($validator->fails()) {
            return $this->renderForm($portfolio, $clean, $validator->errors(), 422);
        }

        try {
            $attributes = PortfolioForm::toAttributes($clean);
            if ($portfolio) {
                $portfolio->update($attributes);
                $message = 'Portfolio updated successfully.';
            } else {
                $portfolio = Portfolio::create($attributes + ['selected_template' => 'simple']);
                $message = 'Portfolio saved successfully.';
            }
        } catch (\Throwable $e) {
            report($e);

            return $this->renderForm(
                $portfolio,
                $clean,
                new MessageBag(['form' => ['Unable to save your portfolio. Please try again.']]),
                500
            );
        }

        session(['last_portfolio_id' => $portfolio->id]);

        if ($action === 'continue') {
            return redirect()->route('templates', $portfolio->id);
        }

        return redirect()->route('portfolios.edit', $portfolio->id)->with('status', $message);
    }

    // ------------------------------------------------------------------ template selection

    public function templatesRedirect(): RedirectResponse
    {
        $id = session('last_portfolio_id');
        if ($id && Portfolio::whereKey($id)->exists()) {
            return redirect()->route('templates', $id);
        }

        return redirect()->route('portfolios.create');
    }

    public function templates(string $id): View
    {
        $portfolio = Portfolio::findOrFail($id);
        session(['last_portfolio_id' => $portfolio->id]);

        return view('portfolios.templates', [
            'portfolio' => $portfolio,
            'selected' => $portfolio->selected_template,
        ]);
    }

    public function saveTemplate(Request $request, string $id): RedirectResponse
    {
        $portfolio = Portfolio::findOrFail($id);

        $data = $request->validate(
            ['template' => ['required', Rule::in(array_keys(Tpl::TEMPLATES))]],
            ['template.required' => 'Please choose a template.', 'template.in' => 'Please choose Simple, Modern, or Creative.']
        );

        $portfolio->update(['selected_template' => $data['template']]);

        return redirect()->route('preview', $portfolio->id);
    }

    // ------------------------------------------------------------------ preview

    public function preview(Request $request, string $id): View
    {
        $portfolio = Portfolio::findOrFail($id);
        session(['last_portfolio_id' => $portfolio->id]);

        $view = (string) $request->query('view', '');
        if (! isset(Tpl::TEMPLATES[$view])) {
            $view = $portfolio->selected_template;
        }

        return view('portfolios.preview', [
            'portfolio' => $portfolio,
            'view' => $view,
            'changed' => $view !== $portfolio->selected_template,
        ]);
    }

    public function savePreview(Request $request, string $id): RedirectResponse
    {
        $portfolio = Portfolio::findOrFail($id);

        $data = $request->validate(
            ['template' => ['required', Rule::in(array_keys(Tpl::TEMPLATES))]],
            ['template.*' => 'Please choose Simple, Modern, or Creative.']
        );

        $portfolio->update(['selected_template' => $data['template']]);

        return redirect()->route('preview', $portfolio->id)->with('status', 'Portfolio updated successfully.');
    }

    // ------------------------------------------------------------------ delete

    public function confirmDelete(string $id): View
    {
        return view('portfolios.confirm-delete', ['portfolio' => Portfolio::findOrFail($id)]);
    }

    public function destroy(string $id): RedirectResponse
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->delete();

        if ((string) session('last_portfolio_id') === (string) $id) {
            session()->forget('last_portfolio_id');
        }

        return redirect()->route('portfolios.index')->with('status', 'Portfolio deleted successfully.');
    }

    // ------------------------------------------------------------------ JSON (read only)

    public function apiIndex(): JsonResponse
    {
        $portfolios = Portfolio::query()
            ->select(['id', 'full_name', 'email', 'selected_template', 'created_at', 'updated_at'])
            ->orderByDesc('updated_at')
            ->get();

        return response()->json(['portfolios' => $portfolios]);
    }

    public function apiShow(string $id): JsonResponse
    {
        return response()->json(['portfolio' => Portfolio::findOrFail($id)]);
    }
}
