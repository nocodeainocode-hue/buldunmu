@include('partials.cards.visual', [
    'company' => $company,
    'premium' => $premium ?? $company->is_premium ?? false,
])
