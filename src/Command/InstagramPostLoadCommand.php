<?php

namespace FMDD\SyliusMarketingPlugin\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectManager;
use FMDD\SyliusMarketingPlugin\Entity\InstagramPost;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class InstagramPostLoadCommand extends Command
{
    protected static $defaultName = 'fmdd:instagram-post:load';

    private EntityRepository $instagramPostRepository;

    private ObjectManager $em;

    protected function configure()
    {
        $this
            ->setName('InstagramPostLoad')
            ->setDescription('Load latest instagram posts');
    }

    public function __construct(
        Registry $doctrine,
        EntityRepository $instagramPostRepository
    )
    {
        parent::__construct();
        $this->em = $doctrine->getManager();
        $this->instagramPostRepository = $instagramPostRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/mcprotech/?__a=1");
        $result = curl_exec($ch);
        curl_close($ch);

        $object = json_decode($result);

        $imported = 0;
        $updated = 0;

        foreach($object->graphql->user->edge_owner_to_timeline_media->edges as $edge) {
            $link = 'https://www.instagram.com/p/' .$edge->node->shortcode . '/';

            /** @var InstagramPost $instagramPost */
            if(($instagramPost = $this->instagramPostRepository->findOneBy(['link' => $link])) === null) {
                $instagramPost = $this->newInstagramPost($edge->node, $link);
                $this->em->persist($instagramPost);
                $imported++;
            } else {
                $instagramPost->setLikes($edge->node->edge_liked_by->count);
                $instagramPost->setComments($edge->node->edge_media_to_comment->count);
                $this->em->persist($instagramPost);
                $updated++;
            }
        }

        $this->em->flush();
        $io->success("Instagram posts imported: " . $imported . ", updated: " . $updated);
        return 0;
    }

    /**
     * @param $node
     * @param $link
     * @return InstagramPost
     */
    private function newInstagramPost($node, $link)
    {
        $instagramPost = new InstagramPost();
        $instagramPost->setAuthor($node->owner->username);
        $instagramPost->setContent($node->edge_media_to_caption->edges[0]->node->text);
        $instagramPost->setLink($link);
        $instagramPost->setLikes($node->edge_liked_by->count);
        $instagramPost->setComments($node->edge_media_to_comment->count);
        $instagramPost->setImageSmall($node->thumbnail_resources[0]->src);
        $instagramPost->setImageMedium($node->thumbnail_resources[2]->src);
        $instagramPost->setImageBig($node->thumbnail_resources[4]->src);
        return $instagramPost;
    }
}
