<?php

namespace App\Jobs;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class HydrateMetadataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $id;
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // for ($i = 0; $i < 1000; $i++) {
        //     Job::find($this->id)->update([
        //         'title' => 'test' . $i,
        //     ]);

        //     Log::info('Hydrated metadata for job id: ' . $this->id . ', iteration : ' . $i);
        // }

        $job = Job::with([
            'educations' => [
                'education'
            ],
            'experiences' => [
                'experience'
            ],
            'industries' => [
                'industry'
            ],
            'salary_brackets' => [
                'salary'
            ],
            'skill_types' => [
                'skill'
            ],
            'locations' => [
                'state',
                'district',
                'city'
            ]
        ])->findOrFail($this->id);
        $metadata = (implode(',',   [
            strtolower($job->title),
            strtolower(strip_tags(htmlspecialchars_decode($job->description))),
            strtolower($job->educations->pluck('education.name', 'education.id')->implode(',')),
            str_replace('.', '', strtolower($job->educations->pluck('education.name', 'education.id')->implode(','))),
            $job->experiences->pluck('experience.value', 'experience.id')->implode(','),
            strtolower($job->industries->pluck('industry.name', 'industry.id')->implode(',')),
            $job->salary_brackets->pluck('salary.name', 'salary.id')->implode(','),
            strtolower(str_replace('.', '',  $job->skill_types->pluck('skill.name', 'skill.id')->implode(','))),
            strtolower($job->skill_types->pluck('skill.name', 'skill.id')->implode(',')),
            strtolower($job->locations->pluck('state.name', 'state.id')
                ->merge($job->locations->pluck('district.name', 'district.id'))
                ->merge($job->locations->pluck('city.name', 'city.id'))->implode(',')),
        ]));

        $job->update([
            'metadata' => $metadata,
        ]);
    }
}
