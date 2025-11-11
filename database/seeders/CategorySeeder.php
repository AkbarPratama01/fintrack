<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // Income Categories
            ['name' => 'Salary', 'type' => 'income', 'icon' => '💼', 'color' => '#10B981', 'user_id' => null],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => '💻', 'color' => '#3B82F6', 'user_id' => null],
            ['name' => 'Investment', 'type' => 'income', 'icon' => '📈', 'color' => '#8B5CF6', 'user_id' => null],
            ['name' => 'Gift', 'type' => 'income', 'icon' => '🎁', 'color' => '#EC4899', 'user_id' => null],
            ['name' => 'Other Income', 'type' => 'income', 'icon' => '💰', 'color' => '#14B8A6', 'user_id' => null],
            
            // Expense Categories
            ['name' => 'Food & Dining', 'type' => 'expense', 'icon' => '🍔', 'color' => '#F59E0B', 'user_id' => null],
            ['name' => 'Transportation', 'type' => 'expense', 'icon' => '🚗', 'color' => '#6366F1', 'user_id' => null],
            ['name' => 'Shopping', 'type' => 'expense', 'icon' => '🛍️', 'color' => '#EC4899', 'user_id' => null],
            ['name' => 'Bills & Utilities', 'type' => 'expense', 'icon' => '💡', 'color' => '#EF4444', 'user_id' => null],
            ['name' => 'Entertainment', 'type' => 'expense', 'icon' => '🎮', 'color' => '#8B5CF6', 'user_id' => null],
            ['name' => 'Healthcare', 'type' => 'expense', 'icon' => '🏥', 'color' => '#10B981', 'user_id' => null],
            ['name' => 'Education', 'type' => 'expense', 'icon' => '📚', 'color' => '#3B82F6', 'user_id' => null],
            ['name' => 'Housing', 'type' => 'expense', 'icon' => '🏠', 'color' => '#F97316', 'user_id' => null],
            ['name' => 'Other Expense', 'type' => 'expense', 'icon' => '📦', 'color' => '#6B7280', 'user_id' => null],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}