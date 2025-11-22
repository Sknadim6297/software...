<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_name',
        'company_name',
        'number',
        'alternate_number',
        'email',
        'project_type',
        'project_valuation',
        'project_start_date',
        'payment_terms',
        'custom_payment_terms',
        'added_date',
        'lead_source',
        'address',
        'gst_number',
        'state_code',
        'state_name',
        'remarks',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'project_valuation' => 'decimal:2',
        'project_start_date' => 'date',
        'added_date' => 'date',
    ];

    /**
     * Get the invoices for the customer.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the contracts for the customer.
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class, 'customer_phone', 'number');
    }

    /**
     * Get available project types with their configurations
     */
    public static function getProjectTypes()
    {
        return [
            'website' => [
                'name' => 'Website',
                'default_valuation_range' => ['min' => 15000, 'max' => 500000],
                'payment_terms' => [
                    '30% Advance + 70% on Delivery',
                    '50% Advance + 50% on Delivery',
                    '40% Advance + 30% on Design + 30% on Delivery',
                    '25% Advance + 25% on Design + 25% on Development + 25% on Delivery'
                ]
            ],
            'application' => [
                'name' => 'Application',
                'default_valuation_range' => ['min' => 50000, 'max' => 1500000],
                'payment_terms' => [
                    '40% Advance + 60% on Delivery',
                    '30% Advance + 30% on Milestone 1 + 40% on Delivery',
                    '25% Advance + 25% on Design + 25% on Development + 25% on Launch',
                    '20% Advance + 20% on Design + 30% on Development + 30% on Testing & Launch'
                ]
            ],
            'software' => [
                'name' => 'Software',
                'default_valuation_range' => ['min' => 100000, 'max' => 5000000],
                'payment_terms' => [
                    '50% Advance + 50% on Delivery',
                    '30% Advance + 30% on Milestone 1 + 40% on Delivery',
                    '25% Advance + 25% on Analysis + 25% on Development + 25% on Deployment',
                    '20% Advance + 20% on Design + 30% on Development + 30% on Testing & Deployment'
                ]
            ],
            'digital_marketing' => [
                'name' => 'Digital Marketing (YouTube, SEO, SMO)',
                'default_valuation_range' => ['min' => 5000, 'max' => 50000],
                'payment_terms' => [
                    'Monthly Payment in Advance',
                    'Quarterly Payment in Advance',
                    '50% Advance + Monthly Billing',
                    'Custom Payment Schedule'
                ]
            ],
            'seo' => [
                'name' => 'SEO',
                'default_valuation_range' => ['min' => 8000, 'max' => 100000],
                'payment_terms' => [
                    'Monthly Payment in Advance',
                    'Quarterly Payment in Advance (10% Discount)',
                    'Half-Yearly Payment in Advance (15% Discount)',
                    'Annual Payment in Advance (20% Discount)'
                ]
            ],
            'smo' => [
                'name' => 'SMO',
                'default_valuation_range' => ['min' => 5000, 'max' => 75000],
                'payment_terms' => [
                    'Monthly Payment in Advance',
                    'Quarterly Payment in Advance (8% Discount)', 
                    'Half-Yearly Payment in Advance (12% Discount)',
                    'Custom Campaign-based Payment'
                ]
            ]
        ];
    }

    /**
     * Get formatted project type name
     */
    public function getFormattedProjectTypeAttribute()
    {
        $projectTypes = self::getProjectTypes();
        return $projectTypes[$this->project_type]['name'] ?? ucwords(str_replace('_', ' ', $this->project_type));
    }

    /**
     * Get available payment terms for this customer's project type
     */
    public function getAvailablePaymentTerms()
    {
        $projectTypes = self::getProjectTypes();
        return $projectTypes[$this->project_type]['payment_terms'] ?? [];
    }

    /**
     * Get the effective payment terms (custom or standard)
     */
    public function getEffectivePaymentTerms()
    {
        if ($this->payment_terms === 'custom' && $this->custom_payment_terms) {
            return $this->custom_payment_terms;
        }
        return $this->payment_terms;
    }

    /**
     * Get valuation range for the current project type
     */
    public function getValuationRange()
    {
        $projectTypes = self::getProjectTypes();
        return $projectTypes[$this->project_type]['default_valuation_range'] ?? ['min' => 0, 'max' => 1000000];
    }
}
