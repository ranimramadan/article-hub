<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ArticleTransition;

class UserAreaDemoSeeder extends Seeder
{
    public function run(): void
    {
        
        $user = User::latest('id')->first();
        if (! $user) {
            $user = User::create([
                'name'     => 'Demo User',
                'email'    => 'demo@example.com',
                'password' => Hash::make('password'),
            ]);
            
            if (method_exists($user, 'assignRole')) {
                try { $user->assignRole('author'); } catch (\Throwable $e) {}
            }
            $this->command->warn('تم إنشاء مستخدم تجريبي: demo@example.com / password');
        }

        
        $catNews   = Category::firstOrCreate(['name' => 'أخبار']);
        $catTravel = Category::firstOrCreate(['name' => 'سفر']);
        $tagPHP    = Tag::firstOrCreate(['name' => 'PHP']);
        $tagLaravel= Tag::firstOrCreate( ['name' => 'Laravel']);

        // مقالات للمستخدم
        $draft = Article::firstOrCreate(
            ['user_id' => $user->id, 'title' => 'مسودة: كيف أبدأ؟'],
            [
                'body'   => 'هذه مسودة تجريبية… عدّلي واحفظي ثم قدّميها للمراجعة.',
                'status' => 'draft',
            ]
        );

        $pending = Article::firstOrCreate(
            ['user_id' => $user->id, 'title' => 'بانتظار المراجعة: رحلتي الأولى'],
            [
                'body'   => 'هذا المقال بانتظار مراجعة الأدمن.',
                'status' => 'pending',
            ]
        );

        $published = Article::firstOrCreate(
            ['user_id' => $user->id, 'title' => 'منشور: 5 نصائح للكتابة'],
            [
                'body'         => 'مقال منشور للتجربة.',
                'status'       => 'published',
                'published_at' => now()->subDays(2),
            ]
        );

        
        $draft->categories()->syncWithoutDetaching([$catNews->id]);
        $draft->tags()->syncWithoutDetaching([$tagPHP->id]);

        $pending->categories()->syncWithoutDetaching([$catTravel->id]);
        $pending->tags()->syncWithoutDetaching([$tagLaravel->id, $tagPHP->id]);

        $published->categories()->syncWithoutDetaching([$catNews->id, $catTravel->id]);
        $published->tags()->syncWithoutDetaching([$tagLaravel->id]);

        
        if (class_exists(ArticleTransition::class)) {
            ArticleTransition::updateOrCreate([
                'article_id'  => $pending->id,
                'from_status' => 'draft',
                'to_status'   => 'pending',
            ],[
                'acted_by'    => $user->id,
                'note'        => 'طلب نشر للمراجعة.',
            ]);

            ArticleTransition::updateOrCreate([
                'article_id'  => $published->id,
                'from_status' => 'pending',
                'to_status'   => 'published',
            ],[
                'acted_by'    => $user->id,
                'note'        => 'تم النشر للتجربة.',
            ]);
        }

        $this->command->info('تم تزويد مقالات تجريبية للمستخدم: '.$user->email);
    }
}
