<?

require __DIR__ . '/vendor/autoload.php';

use Orhanerday\OpenAi\OpenAi;

$open_ai_key = getenv('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');
$open_ai = new OpenAi($open_ai_key);

$prompt = $_POST['prompt'];

$chat = $open_ai->chat([
    'model' => 'gpt-4o-mini',
    'prompt' => 'say hi dudeeeee!',
    'messages' => [
        [
            "role" => "system",
            "content" => "You are a helpful assistant."
        ],
        [
            "role" => "user",
            "content" => "Who won the world series in 2020?"
        ],
        [
            "role" => "assistant",
            "content" => "The Los Angeles Dodgers won the World Series in 2020."
        ],
        [
            "role" => "user",
            "content" => "Where was it played?"
        ],
    ],
    'temperature' => 1.0,
    'max_tokens' => 4000,
    'frequency_penalty' => 0,
    'presence_penalty' => 0,
]);


var_dump($chat);
echo "<br>";
echo "<br>";
echo "<br>";
// decode response
$d = json_decode($chat);
// Get Content
echo ($d->choices[0]->message->content);
