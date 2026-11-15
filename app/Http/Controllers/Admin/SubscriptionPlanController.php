<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $plans = $this->plansQuery($search)->get();
        $totalPlans = SubscriptionPlan::count();
        $activePlans = SubscriptionPlan::where('status', true)->count();
        $inactivePlans = SubscriptionPlan::where('status', false)->count();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.subscription-plans.partials.table', compact('plans'))->render(),
                'totalPlans' => $totalPlans,
                'activePlans' => $activePlans,
                'inactivePlans' => $inactivePlans,
                'recordsFound' => $plans->count(),
            ]);
        }

        return view('admin.subscription-plans.index', compact(
            'plans',
            'search',
            'totalPlans',
            'activePlans',
            'inactivePlans'
        ));
    }

    public function create()
    {
        return response()->json([
            'plan' => [
                'id' => null,
                'plan_name' => '',
                'description' => '',
                'duration' => '',
                'duration_type' => 'Months',
                'price' => '',
                'status' => '1',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $plan = SubscriptionPlan::create($this->validatedData($request));

        return response()->json([
            'message' => 'Subscription plan created successfully.',
            'plan' => $plan,
        ]);
    }

    public function edit($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        return response()->json([
            'plan' => $plan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->update($this->validatedData($request));

        return response()->json([
            'message' => 'Subscription plan updated successfully.',
            'plan' => $plan,
        ]);
    }

    public function view($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        return response()->json([
            'plan' => [
                'plan_name' => $plan->plan_name,
                'description' => $plan->description ?: 'N/A',
                'duration' => $plan->duration.' '.$plan->duration_type,
                'price' => number_format((float) $plan->price, 2),
                'status' => $plan->status ? 'Active' : 'Inactive',
                'popular' => $plan->is_popular ? 'Yes' : 'No',
                'created_date' => $plan->created_at->format('d M Y'),
                'updated_date' => $plan->updated_at->format('d M Y'),
            ],
        ]);
    }

    public function delete($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->delete();

        return response()->json([
            'message' => 'Subscription plan deleted successfully.',
        ]);
    }

    public function togglePopular($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $makePopular = ! $plan->is_popular;

        if ($makePopular) {
            SubscriptionPlan::where('id', '!=', $plan->id)->update(['is_popular' => false]);
        }

        $plan->update(['is_popular' => $makePopular]);

        return response()->json([
            'message' => $makePopular
                ? "{$plan->plan_name} marked as popular."
                : "{$plan->plan_name} removed from popular.",
            'is_popular' => $plan->is_popular,
        ]);
    }

    private function plansQuery(string $search)
    {
        return SubscriptionPlan::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('plan_name', 'like', "%{$search}%")
                        ->orWhere('duration_type', 'like', "%{$search}%")
                        ->orWhere('price', 'like', "%{$search}%");
                });
            })
            ->latest();
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'plan_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration' => ['required', 'numeric', 'min:1'],
            'duration_type' => ['required', Rule::in(['Days', 'Months', 'Years'])],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['1', '0', 1, 0])],
        ]);
    }
}
