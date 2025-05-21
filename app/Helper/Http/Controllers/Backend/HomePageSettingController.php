<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gender;
use App\Models\HomePageSetting;
use Illuminate\Http\Request;

class HomePageSettingController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->get();
        $genders = Gender::where('status', 1)->get();
        $popularCategorySection = HomePageSetting::where('key', 'popular_category_section')->first();
        $sliderSectionOne = HomePageSetting::where('key', 'product_slider_section_one')->first();
        $sliderSectionTwo = HomePageSetting::where('key', 'product_slider_section_two')->first();
        $sliderSectionThree = HomePageSetting::where('key', 'product_slider_section_three')->first();
        $sliderSectionGenero = HomePageSetting::where('key', 'genero_slider_section')->first();
        
        return view('admin.home-page-setting.index', compact('categories', 'genders', 'popularCategorySection', 'sliderSectionOne', 'sliderSectionTwo', 'sliderSectionThree', 'sliderSectionGenero'));
    }


    public function updatePopularCategorySection(Request $request)
    {
        $request->validate([
            'cat_one' => ['required'],
            'cat_two' => ['required'],
            'cat_three' => ['required'],
            'cat_four' => ['required']

        ], [
            'cat_one.required' => 'Category one filed is required',
            'cat_two.required' => 'Category two filed is required',
            'cat_three.required' => 'Category three filed is required',
            'cat_four.required' => 'Category four filed is required',
        ]);

        // dd($request->all());
        $data = [
            [
                'category' => $request->cat_one,
                'sub_category' => $request->sub_cat_one,
                'child_category' => $request->child_cat_one,
            ],
            [
                'category' => $request->cat_two,
                'sub_category' => $request->sub_cat_two,
                'child_category' => $request->child_cat_two,
            ],
            [
                'category' => $request->cat_three,
                'sub_category' => $request->sub_cat_three,
                'child_category' => $request->child_cat_three,
            ],
            [
                'category' => $request->cat_four,
                'sub_category' => $request->sub_cat_four,
                'child_category' => $request->child_cat_four,
            ]
        ];

        HomePageSetting::updateOrCreate(
            [
                'key' => 'popular_category_section'
            ],
            [
                'value' => json_encode($data)
            ]
        );

        toastr('Updated successfully!', 'success', 'success');

        return redirect()->back();
    }

    public function updateProductSliderSectionOn(Request $request)
    {
        $request->validate([
            'cat_one' => ['required']
        ], [
            'cat_one.required' => 'Category filed is required'
        ]);

        $data = [
                'category' => $request->cat_one,
                'sub_category' => $request->sub_cat_one,
                'child_category' => $request->child_cat_one,
            ];

        HomePageSetting::updateOrCreate(
            [
                'key' => 'product_slider_section_one'
            ],
            [
                'value' => json_encode($data)
            ]
        );

        toastr('Updated successfully!', 'success', 'success');

        return redirect()->back();

    }

    public function updateProductSliderSectionTwo(Request $request)
    {
        $request->validate([
            'cat_one' => ['required']
        ], [
            'cat_one.required' => 'Category filed is required'
        ]);

        $data = [
                'category' => $request->cat_one,
                'sub_category' => $request->sub_cat_one,
                'child_category' => $request->child_cat_one,
            ];

        HomePageSetting::updateOrCreate(
            [
                'key' => 'product_slider_section_two'
            ],
            [
                'value' => json_encode($data)
            ]
        );

        toastr('Updated successfully!', 'success', 'success');

        return redirect()->back();
    }

    public function updateProductSliderSectionThree(Request $request)
    {
        $request->validate([
            'cat_one' => ['required'],
            'cat_two' => ['required']
        ], [
            'cat_one.required' => 'Part 1 Category filed is required',
            'cat_two.required' => 'Part 2 Category filed is required'

        ]);

        $data = [
            [
                'category' => $request->cat_one,
                'sub_category' => $request->sub_cat_one,
                'child_category' => $request->child_cat_one,
            ],
            [
                'category' => $request->cat_two,
                'sub_category' => $request->sub_cat_two,
                'child_category' => $request->child_cat_two,
            ]
        ];

        HomePageSetting::updateOrCreate(
            [
                'key' => 'product_slider_section_three'
            ],
            [
                'value' => json_encode($data)
            ]
        );

        toastr('Updated successfully!', 'success', 'success');

        return redirect()->back();
    }

    public function updateProductSliderSectionGenero(Request $request)
    {
        $request->validate([
            'cat_one' => ['required'],
            'cat_two' => ['required']
        ], [
            'cat_one.required' => 'Part 1 Genero filed is required',
            'cat_two.required' => 'Part 2 Genero filed is required'

        ]);

        $data = [
            [
                'gender' => $request->cat_one,
            ],
            [
                'gender' => $request->cat_two,
            ]
        ];

        HomePageSetting::updateOrCreate(
            [
                'key' => 'genero_slider_section'
            ],
            [
                'value' => json_encode($data)
            ]
        );

        toastr('Modificado con Exito
        !', 'success', 'success');

        return redirect()->back();
    }

}
