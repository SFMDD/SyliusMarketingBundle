<?php


namespace FMDD\SyliusMarketingPlugin\Provider;


use Doctrine\Bundle\DoctrineBundle\Registry;
use EspressoDev\InstagramBasicDisplay\InstagramBasicDisplay;
use FMDD\SyliusMarketingPlugin\Entity\InstagramPost;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class InstagramPostsProvider
{
    /** @var HttpClientInterface */
    private HttpClientInterface $client;
    /** @var Registry */
    private Registry $doctrine;

    private $clientId;
    private $clientSecret;

    public function __construct(HttpClientInterface $client, Registry $registry, $clientId, $clientSecret)
    {
        $this->client = $client;
        $this->doctrine = $registry;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function getAccessToken() {
        $query =   "https://graph.facebook.com/oauth/access_token?client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret . "&grant_type=client_credentials";
        try {
            $response = $this->client->request(
                'GET',
                $query,
            );
            return $response->getStatusCode() === 200 ?  json_decode($response->getContent())->access_token : '';
        } catch (TransportExceptionInterface $exception){
            return '';
        }
    }

    public function getPosts($limit = 10) {
        $posts = $this->doctrine->getRepository(InstagramPost::class)->findAll();
        shuffle($posts);
        $posts = array_slice($posts, 0, $limit);
        $tmp = [];
        foreach ($posts as $post){
            $tmp[] = $this->display($post);
        }
        return $tmp;
    }

    public function display(InstagramPost $post) {
        $token = $this->getAccessToken();
        $query = 'https://graph.facebook.com/v10.0/instagram_oembed?url=' . $post->getLink() . '&access_token=' . urldecode($token);
        try {
            $response = $this->client->request(
                'GET',
                $query,
            );
            return 200 === $response->getStatusCode() ? json_decode($response->getContent())->html : '';
        } catch (TransportExceptionInterface $exception){
            return '';
        }
    }
}
