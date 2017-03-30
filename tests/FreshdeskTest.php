<?php
namespace Tests;

require_once(__DIR__ . '/../src/Models/checkRequest.php');

class FreshdeskTest extends BaseTestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testRoutes($url) {
        $response = $this->runApp("POST", '/api/Freshdesk/'.$url);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function dataProvider() {
        return [
            ['getTickets'],
            ['createTicket'],
            ['getSingleTicket'],
            ['getAllTicketFields'],
            ['getContacts'],
            ['getSingleContact'],
            ['getAllContactFields'],
            ['getAllCompanyFields'],
            ['createForumCategory'],
            ['updateForumCategory'],
            ['getForumCategory'],
            ['getAllForumCategories'],
            ['deleteForumCategory'],
            ['updateForum'],
            ['getAllForumsFromCategory'],
            ['getForum'],
            ['deleteForum'],
            ['createTopic'],
            ['updateTopic'],
            ['deleteTopic'],
            ['getSingleTopic'],
            ['createComment'],
            ['updateComment'],
            ['deleteComment'],
            ['monitorTopic'],
            ['unMonitorTopic'],
            ['monitorForum'],
            ['unMonitorForum'],
            ['getUserMonitoredTopic'],
            ['getMonitorStatusForTopic'],
            ['getMonitorStatusForForum'],
            ['updateTicket'],
            ['assignTicket'],
            ['getAllAgents'],
            ['createCompany'],
            ['updateCompany'],
            ['getSingleCompany'],
            ['getAllCompanies'],
            ['deleteCompany'],
            ['deleteCompanyDomains'],
            ['createSolutionCategory'],
            ['updateSolutionCategory'],
            ['createTranslatedSolutionCategory'],
            ['getSolutionCategory'],
            ['getAllSolutionCategories'],
            ['deleteSolutionCategory'],
            ['createSolutionFolder'],
            ['createTranslatedSolutionFolder'],
            ['updateSolutionFolder'],
            ['getSolutionFolder'],
            ['getAllSolutionFolders'],
            ['deleteSolutionFolder'],
            ['createSolutionArticle'],
            ['createTranslatedSolutionArticle'],
            ['updateSolutionArticle'],
            ['getSingleAgent'],
            ['getCurrentlyAgent'],
            ['updateAgent'],
            ['getTicketConversations'],
            ['addNoteToTicket'],
            ['createContact'],
            ['updateContact'],
            ['makeAgent'],
            ['deleteContact'],
            ['deleteAgent'],
            ['createForum'],
        ];
    }
}