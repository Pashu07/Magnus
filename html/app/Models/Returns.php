<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;
    protected $table = 'vat_returns';

    protected $fillable = [
        "client_id",
        "return_from",
        "return_to",
        "tax_year_end",
        "return_reference_number",
        "return_due_date",
        "return _filed_date",
        "srs_abu_dhabhi_amount",
        "srs_abu_dhabhi_vat_amount",
        "srs_abu_dhabhi_adjusted_amount",
        "srs_dubai_amount",
        "srs_dubai_vat_amount",
        "srs_dubai_adjusted_amount",
        "srs_sharjah_amount",
        "srs_sharjah_vat_amount",
        "srs_sharjah_adjusted_amount",
        "srs_ajman_amount",
        "srs_ajman_vat_amount",
        "srs_ajman_adjusted_amount",
        "srs_quwain_amount",
        "srs_quwain_vat_amount",
        "srs_quwain_adjusted_amount",
        "srs_khaimah_amount",
        "srs_khaimah_vat_amount",
        "srs_khaimah_adjusted_amount",
        "srs_fujairah_amount",
        "srs_fujairah_vat_amount",
        "srs_fujairah_adjusted_amount",
        "tax_refund_to_tourists_under_tourists_scheme_amount",
        "tax_refund_to_tourists_under_tourists_scheme_vat_amount",
        "supplies_subject_to_reverse_charge_amount",
        "supplies_subject_to_reverse_charge_vat_amount",
        "zero_rated_supplies_amount",
        "exempt_supplies_amount",
        "goods_imported_in_uae_amount",
        "goods_imported_in_uae_vat_amount",
        "adjustments_goods_imported_into_uae_amount",
        "adjustments_goods_imported_into_uae_vat_amount",
        "adjustments_goods_imported_into_uae_adjusted_amount",
        "main_total_amount",
        "main_total_vat_amount",
        "main_total_adjusted_amount",
        "standard_rated_expenses_amount",
        "standard_rated_expenses_vat_amount",
        "standard_rated_expenses_adjusted_amount",
        "supplies_subject_to_reverse_charge_provisions_amount",
        "supplies_subject_to_reverse_charge_provisions_vat_amount",
        "expense_total_amount",
        "expense_total_vat_amount",
        "expense_total_adjusted_amount",
        "total_value_of_due_tax_period_amount",
        "total_value_of_recoverable_tax_period_amount",
        "payable_tax_for_period_amount",
        "is_draft",
        "created_by",
        "modified_by",
        "updated_at"
    ];

    protected $hidden = [
        'account_id'
    ];
    

    protected $casts = [
        'created_on' => 'datetime'
    ];

}
