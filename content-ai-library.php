<?php
function GetBlogPostIdeas($title)
{
    $prompt = "Generate 5 blog ideas on topic ".$title." ";

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=AIzaSyCbKNDKE9aVwWdnNCYn-czSZn1pkHsyegM',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{ "contents":[{
            "parts":[{"text": "'.$prompt.' with outlines and include summary of points to cover on each subtopic using this JSON schema: \\\\{ \'type\': \'array\', 
            \'properties\': 
            \\\\{ \'topic\': \\\\{ \'type\': \'string\' \\\\},
            \'summary\': \\\\{ \'type\': \'string\' \\\\},
            \'outline\':\\\\{\'type\': \'array\',
                        \'properties\':
                        \\\\{ \'subtopic\': \\\\{ \'type\': \'string\' \\\\},
                        \'subtopic_summary\': \\\\{ \'type\': \'string\' \\\\},
            \\\\}\\\\}"}]}],
          "generationConfig": {
            "response_mime_type": "application/json"
          } }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);

    $json = json_decode($response);
    
    $topics = $json->candidates[0]->content->parts[0]->text;
    curl_close($curl);
    return $topics;

}

function createArticle($data)
{

//  $data["topic"]
//  $data["summary"]
//  $data["outline"]

$topic = json_encode($data["outline"]);
$str_schema = addslashes($topic);

$prompt = "create 1000 word blog post on ".$data["topic"]." formatted in html starting after body tags without CSS formatting using the outline in this JSON schema:".$str_schema;

$content='<p> <img src="img-link"> </p> <p> Hi, this is an example of the content.</p> <a class="dl" href="link-address"> Link Name</a>';

$postData = array(
    'post_title' => $data["topic"],
    'post_status' => 'publish',
    'post_content' => $content,
    'post_author' => 1,
    'post_type'         =>   'post',
    'post_category' => array()
);

$id = wp_insert_post($postData);

return $prompt;

/*

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=AIzaSyCbKNDKE9aVwWdnNCYn-czSZn1pkHsyegM',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{ "contents":[{
            "parts":[{"text": "'.$prompt.'"
            }]}]}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

$json = json_decode($response);

$topics = $json->candidates[0]->content->parts[0]->text;

$topics = json_decode($topics);

//var_dump($json->candidates[0]->content->parts[0]->text);

// Create wordpress post here. This post will be a draft.

$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);
echo $Parsedown->text($json->candidates[0]->content->parts[0]->text);

curl_close($curl);
*/
}

function GenerateIdeas()
{
return '
[{"topic": "The Rise of AI-Powered Marketing", "summary": "Explore how artificial intelligence is transforming digital marketing, from personalized content creation to automated ad campaigns.", "outline": [{"subtopic": "AI-Driven Personalization", "subtopic_summary": "Discuss how AI algorithms analyze user data to deliver tailored experiences, enhancing engagement and conversions."}, {"subtopic": "Automated Marketing Tasks", "subtopic_summary": "Examine the use of AI for automating tasks like email marketing, social media scheduling, and content creation, freeing marketers to focus on strategy."}, {"subtopic": "Predictive Analytics", "subtopic_summary": "Explain how AI-powered analytics provide insights into customer behavior, enabling marketers to anticipate needs and optimize campaigns."}, {"subtopic": "Chatbots and Conversational Marketing", "subtopic_summary": "Highlight the role of AI-powered chatbots in providing instant customer support, gathering feedback, and driving conversions."}, {"subtopic": "Ethical Considerations in AI Marketing", "subtopic_summary": "Address the ethical implications of using AI in marketing, including data privacy, bias, and transparency."}]}, {"topic": "The Metaverse and Immersive Marketing", "summary": "Dive into the emerging trend of the metaverse and how brands are utilizing immersive experiences to connect with consumers.", "outline": [{"subtopic": "Virtual and Augmented Reality Experiences", "subtopic_summary": "Explore how brands are creating virtual and augmented reality experiences to engage consumers, promote products, and enhance brand storytelling."}, {"subtopic": "Interactive Marketing in the Metaverse", "subtopic_summary": "Discuss the potential of the metaverse for interactive marketing, such as virtual events, product demonstrations, and brand activations."}, {"subtopic": "Building Brand Presence in the Metaverse", "subtopic_summary": "Examine how brands are creating virtual spaces, avatars, and experiences to establish a presence in the metaverse."}, {"subtopic": "Marketing Opportunities for Creators and Influencers", "subtopic_summary": "Highlight the role of creators and influencers in promoting brands and products within the metaverse."}, {"subtopic": "Challenges and Future of Metaverse Marketing", "subtopic_summary": "Discuss the challenges and potential future of marketing in the metaverse, including accessibility, adoption, and regulation."}]}, {"topic": "The Power of Short-Form Video", "summary": "Analyze the impact of short-form video platforms like TikTok and Reels on digital marketing strategies.", "outline": [{"subtopic": "Short-Form Video Content Strategies", "subtopic_summary": "Explore different approaches to creating compelling short-form video content, including entertainment, education, and product demonstrations."}, {"subtopic": "Building a Brand Presence on TikTok and Reels", "subtopic_summary": "Offer tips on creating engaging content, building a community, and leveraging the unique features of these platforms."}, {"subtopic": "Influencer Marketing on Short-Form Video", "subtopic_summary": "Discuss the effectiveness of partnering with influencers on short-form video platforms to reach new audiences and drive engagement."}, {"subtopic": "Measuring Success and ROI", "subtopic_summary": "Explain how to track metrics and measure the effectiveness of short-form video marketing campaigns."}, {"subtopic": "The Future of Short-Form Video Marketing", "subtopic_summary": "Predict the future trends and opportunities for short-form video marketing in the coming year."}]}, {"topic": "The Importance of Data Privacy and Security", "summary": "Address the growing concern for data privacy and security in digital marketing, and explore strategies for ethical and responsible data collection and usage.", "outline": [{"subtopic": "The Changing Landscape of Privacy Regulations", "subtopic_summary": "Discuss the evolving legal framework surrounding data privacy, including GDPR, CCPA, and other regulations."}, {"subtopic": "Transparency and Consent in Data Collection", "subtopic_summary": "Explain the importance of being transparent about data collection practices and obtaining explicit consent from users."}, {"subtopic": "Data Minimization and Ethical Data Usage", "subtopic_summary": "Highlight the importance of collecting only the necessary data and using it ethically and responsibly."}, {"subtopic": "Building Trust and Protecting User Data", "subtopic_summary": "Provide strategies for building trust with consumers and ensuring the security of their personal information."}, {"subtopic": "The Future of Data Privacy and Marketing", "subtopic_summary": "Explore the evolving landscape of data privacy and its implications for digital marketing strategies."}]}, {"topic": "The Rise of Sustainable and Ethical Marketing", "summary": "Examine the growing trend of sustainable and ethical practices in digital marketing, focusing on environmental responsibility and social impact.", "outline": [{"subtopic": "Environmental Sustainability in Marketing", "subtopic_summary": "Discuss how brands can reduce their environmental impact through sustainable marketing practices, including eco-friendly packaging, carbon offsetting, and renewable energy use."}, {"subtopic": "Ethical Sourcing and Production", "subtopic_summary": "Highlight the importance of ethical sourcing and production practices, ensuring fair labor conditions and responsible resource management."}, {"subtopic": "Social Impact Marketing Campaigns", "subtopic_summary": "Explore how brands can use their marketing efforts to support social causes, promote diversity and inclusion, and drive positive change."}, {"subtopic": "Transparency and Authenticity in Sustainable Marketing", "subtopic_summary": "Emphasize the need for transparency and authenticity in sustainable marketing claims, building trust with consumers who are increasingly aware of environmental and social issues."}, {"subtopic": "The Future of Sustainable and Ethical Marketing", "subtopic_summary": "Predict the future trends and opportunities for sustainable and ethical marketing, as consumers demand greater accountability and purpose-driven brands."}]}]';
}
?>