<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            'Laravel',
            'Vue.js',
            'React',
            'TypeScript',
            'PHP',
            'JavaScript',
            'Python',
            'Node.js',
            'Rust',
            'Go',
            'SQL',
            'API design',
            'DevOps',
            'Product management',
            'B2B sales',
            'B2C sales',
            'Marketing',
            'Fundraising',
            'UX design',
            'Technical leadership',
            'Business development',
        ];

        foreach ($skills as $name) {
            Skill::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
