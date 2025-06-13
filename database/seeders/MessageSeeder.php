<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all topics
        $topics = Topic::all();

        // Find the user from documentation
        $user = User::where('email', 'user@example.com')->first();

        if (! $user) {
            $user = User::factory()->create([
                'email' => 'user@example.com',
                'first_name' => 'Иван',
                'last_name' => 'Иванов',
                'middle_name' => 'Иванович',
            ]);
        }

        // Ensure storage is set up for testing
        Storage::fake('public');

        // Create messages for each topic
        foreach ($topics as $topic) {
            // Create 2-4 messages from the topic owner (user)
            $userMessages = Message::factory()
                ->count(rand(2, 4))
                ->create([
                    'user_id' => $topic->user_id,
                    'topic_id' => $topic->id,
                ]);

            // Add attachments to some user messages
            foreach ($userMessages as $index => $message) {
                // Add 1-3 attachments to every other message
                if ($index % 2 === 0) {
                    $this->addAttachmentsToMessage($message, rand(1, 3));
                }
            }

            // Create 1-3 messages from the user in documentation
            $docUserMessages = Message::factory()
                ->count(rand(1, 3))
                ->create([
                    'user_id' => $user->id,
                    'topic_id' => $topic->id,
                ]);

            // Add attachments to some documentation user messages
            foreach ($docUserMessages as $index => $message) {
                // Add 1-2 attachments to every other message
                if ($index % 2 === 0) {
                    $this->addAttachmentsToMessage($message, rand(1, 2));
                }
            }
        }
    }

    /**
     * Add attachments to a message
     *
     * @param  Message  $message  The message to add attachments to
     * @param  int  $count  Number of attachments to add
     */
    private function addAttachmentsToMessage(Message $message, int $count): void
    {
        // Make sure we're using the public disk for testing
        Storage::fake('public');

        for ($i = 0; $i < $count; $i++) {
            // Create a fake image with random dimensions
            $file = UploadedFile::fake()->image(
                "attachment_{$message->id}_{$i}.jpg",
                rand(400, 800),  // width
                rand(400, 800)   // height
            );

            // Add the file to the message's media collection
            $message->addMedia($file)
                ->withCustomProperties(['message_id' => $message->id])
                ->toMediaCollection(Message::ATTACHMENTS_COLLECTION);
        }
    }
}
