[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/Freshdesk/functions?utm_source=RapidAPIGitHub_FreshdeskFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)
# Freshdesk Package
Manage customer support tickets, agents and monitoring.
* Domain: [Freshdesk](https://freshdesk.com/)
* Credentials: apiKey, domain

## How to get credentials: 
1. Get ApiKey from your profile settings.
 
 
## Custom datatypes: 
 |Datatype|Description|Example
 |--------|-----------|----------
 |Datepicker|String which includes date and time|```2016-05-28 00:00:00```
 |Map|String which includes latitude and longitude coma separated|```50.37, 26.56```
 |List|Simple array|```["123", "sample"]``` 
 |Select|String with predefined values|```sample```
 |Array|Array of objects|```[{"Second name":"123","Age":"12","Photo":"sdf","Draft":"sdfsdf"},{"name":"adi","Second name":"bla","Age":"4","Photo":"asfserwe","Draft":"sdfsdf"}] ```
 
## Freshdesk.getAllTickets
Use filters to view only specific tickets (those which match the criteria that you choose). By default only tickets that have not been deleted or marked as spam will be returned, unless you use the 'deleted' filter.

| Field       | Type       | Description
|-------------|------------|----------
| apiKey      | credentials| Api Key
| domain      | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| filter      | Select     | The various filters available are new_and_my_open, watching, spam, deleted
| userId      | Number     | Find Tickets by userId
| email       | String     | Find Tickets by e-mail
| companyId   | String     | Find Tickets be Company ID
| updatedSince| String     | By default only tickets that have been created within the past 30 days will be returned. For older tickets, use this field. Example: 2017-03-27 (ISO 8601)
| orderBy     | Select     | Order Tickets by created_at, due_by, updated_at, status. Default: created_at
| orderType   | Select     | asc/desc. Default: desc

## Freshdesk.getSingleTicket
Find Ticket by ID. By default, certain fields such as conversations, company name and requester email will not be included in the response. They can be retrieved via the embedding functionality for extra API request points.

| Field               | Type       | Description
|---------------------|------------|----------
| apiKey              | credentials| Api Key
| domain              | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| ticketId            | Number     | Ticket ID
| includeConversations| Boolean    | Will return ten conversations sorted by 'created_at' in ascending order. Including conversations will consume two API calls. In order to access more than ten conversations belonging to a ticket, use the getTicketConversations. Default: false
| includeRequester    | Boolean    | Will return the requester's email, id, mobile, name, and phone. Default: false
| includeCompany      | Boolean    | Will return the company's id and name. Default: false
| includeStats        | String     | Will return the ticket’s closed_at, resolved_at and first_responded_at time. Default: false

## Freshdesk.getAllTicketFields
Get all Ticket fields

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com

## Freshdesk.getContacts
Get all contacts

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com

## Freshdesk.getSingleContact
Get user by id

| Field    | Type       | Description
|----------|------------|----------
| apiKey   | credentials| Api Key
| domain   | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| contactId| Number     | Contact ID

## Freshdesk.getAllContactFields
Get all field for concact

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com

## Freshdesk.createTicket
Create a new Ticket

| Field        | Type       | Description
|--------------|------------|----------
| apiKey       | credentials| Api Key
| domain       | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| name         | String     | Name of the requester
| requesterId  | number     | User ID of the requester. For existing contacts, the requester_id can be passed instead of the requester's email.
| email        | string     | Email address of the requester. If no contact exists with this email address in Freshdesk, it will be added as a new contact.
| facebookId   | string     | Facebook ID of the requester. If no contact exists with this facebook_id, then a new contact will be created.
| phone        | string     | Phone number of the requester. If no contact exists with this phone number in Freshdesk, it will be added as a new contact. If the phone number is set and the email address is not, then the name attribute is mandatory.
| twitterId    | string     | Twitter handle of the requester. If no contact exists with this handle in Freshdesk, it will be added as a new contact.
| subject      | string     | Subject of the ticket. The default Value is null.
| type         | string     | Helps categorize the ticket according to the different kinds of issues your support team deals with. The default Value is null.
| status       | number     | Status of the ticket. The default Value is 2.
| priority     | number     | Priority of the ticket. The default value is 1.
| description  | string     | HTML content of the ticket.
| responderId  | number     | ID of the agent to whom the ticket has been assigned
| attachments  | List       | Array of file links (url). The total size of these attachments cannot exceed 15MB.
| ccEmails     | List       | Array of email address added in the 'cc' field of the incoming ticket email
| customFields | Array      | Key value pairs containing the names and values of custom fields. Example: 'custom_fields':{'gadget':'Cold Welder'}
| dueBy        | DatePicker | Timestamp that denotes when the ticket is due to be resolved
| emailConfigId| Number     | ID of email config which is used for this ticket. (i.e.,  support@yourcompany.com/sales@yourcompany.com) If product_id is given and email_config_id is not given, product's primary email_config_id will be set
| frDueBy      | DatePicker | Timestamp that denotes when the first response is due
| groupId      | Number     | ID of the group to which the ticket has been assigned. The default value is the ID of the group that is associated with the given email_config_id
| productId    | Number     | ID of the product to which the ticket is associated. It will be ignored if the email_config_id attribute is set in the request.
| source       | Number     | The channel through which the ticket was created. The default value is 2.
| tags         | List       | Array of tags that have been associated with the ticket
| companyId    | Number     | Company ID of the requester. This attribute can only be set if the Multiple Companies feature is enabled (Estate plan and above)

## Freshdesk.updateTicket
Update Ticket

| Field        | Type       | Description
|--------------|------------|----------
| apiKey       | credentials| Api Key
| domain       | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| name         | String     | Name of the requester
| requesterId  | number     | User ID of the requester. For existing contacts, the requester_id can be passed instead of the requester's email.
| email        | string     | Email address of the requester. If no contact exists with this email address in Freshdesk, it will be added as a new contact.
| facebookId   | string     | Facebook ID of the requester. If no contact exists with this facebook_id, then a new contact will be created.
| phone        | string     | Phone number of the requester. If no contact exists with this phone number in Freshdesk, it will be added as a new contact. If the phone number is set and the email address is not, then the name attribute is mandatory.
| twitterId    | string     | Twitter handle of the requester. If no contact exists with this handle in Freshdesk, it will be added as a new contact.
| subject      | string     | Subject of the ticket. The default Value is null.
| type         | string     | Helps categorize the ticket according to the different kinds of issues your support team deals with. The default Value is null.
| status       | number     | Status of the ticket. The default Value is 2.
| priority     | number     | Priority of the ticket. The default value is 1.
| description  | string     | HTML content of the ticket.
| responderId  | number     | ID of the agent to whom the ticket has been assigned
| attachments  | List       | Array of file links (url). The total size of these attachments cannot exceed 15MB. You can only add new attachments to existed.
| ccEmails     | List       | Array of email address added in the 'cc' field of the incoming ticket email
| customFields | Array      | Key value pairs containing the names and values of custom fields. Example: 'custom_fields':{'gadget':'Cold Welder'}
| dueBy        | DatePicker | Timestamp that denotes when the ticket is due to be resolved
| emailConfigId| Number     | ID of email config which is used for this ticket. (i.e.,  support@yourcompany.com/sales@yourcompany.com) If product_id is given and email_config_id is not given, product's primary email_config_id will be set
| frDueBy      | DatePicker | Timestamp that denotes when the first response is due
| groupId      | Number     | ID of the group to which the ticket has been assigned. The default value is the ID of the group that is associated with the given email_config_id
| productId    | Number     | ID of the product to which the ticket is associated. It will be ignored if the email_config_id attribute is set in the request.
| source       | Number     | The channel through which the ticket was created. The default value is 2.
| tags         | List       | Array of tags that have been associated with the ticket
| companyId    | Number     | Company ID of the requester. This attribute can only be set if the Multiple Companies feature is enabled (Estate plan and above)

## Freshdesk.getAllCompanyFields
Get all Company fields

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com

## Freshdesk.createForumCategory
Create Forum category

| Field      | Type       | Description
|------------|------------|----------
| apiKey     | credentials| Api Key
| domain     | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| name       | String     | Unique name of the forum category
| description| String     | Description of the forum category

## Freshdesk.createForum
Create Forum

| Field          | Type       | Description
|----------------|------------|----------
| apiKey         | credentials| Api Key
| domain         | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| name           | String     | Unique name of the forum category
| categoryId     | Number     | category ID
| forumType      | Select     | Denotes the type of forum. 1 - How To's, 2- Ideas, 3 -Problems, 4 - Announcements
| forumVisibility| Select     | Denotes the visibility level of the forum. 1 - Everyone, 2 - Logged in users only, 3 - Agents only, 4 - Users in specific companies only
| description    | String     | Description of the forum category
| companyIdList  | List       | List of CompanyID. If forumVisibility property is set to 4, the forum is only visible to users belonging to certain companies. Example: 1,2,3

## Freshdesk.updateForumCategory
Update Name or Description of Category

| Field      | Type       | Description
|------------|------------|----------
| apiKey     | credentials| Api Key
| domain     | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| categoryId | Number     | Category ID
| name       | String     | Unique name of the forum category
| description| String     | Description of the forum category

## Freshdesk.getForumCategory
Get Forum Category by ID

| Field     | Type       | Description
|-----------|------------|----------
| apiKey    | credentials| Api Key
| domain    | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| categoryId| Number     | Category ID

## Freshdesk.getAllForumCategories
Get All Forum Category

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com

## Freshdesk.deleteForumCategory
Delete Forum Category by ID

| Field     | Type       | Description
|-----------|------------|----------
| apiKey    | credentials| Api Key
| domain    | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| categoryId| Number     | Category ID

## Freshdesk.updateForum
Update Forum by ID

| Field          | Type       | Description
|----------------|------------|----------
| apiKey         | credentials| Api Key
| domain         | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| forumId        | Number     | Forum ID
| name           | String     | Unique name of Forum
| forumType      | Select     | Denotes the type of forum. 1 - How To's, 2- Ideas, 3 -Problems, 4 - Announcements
| forumVisibility| Select     | Denotes the visibility level of the forum. 1 - Everyone, 2 - Logged in users only, 3 - Agents only, 4 - Users in specific companies only
| description    | String     | Description of the forum category
| companyIdList  | List      | List of CompanyID. If forumVisibility property is set to 4, the forum is only visible to users belonging to certain companies. Example: 1,2,3
| forumCategoryId| Number     | ID of the category to which this forum belongs

## Freshdesk.getAllForumsFromCategory
Get All forums in given Category

| Field     | Type       | Description
|-----------|------------|----------
| apiKey    | credentials| Api Key
| domain    | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| categoryId| Number     | Category ID

## Freshdesk.getForum
Get Forum by ID

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| forumId| Number     | Forum ID

## Freshdesk.deleteForum
Delete Forum

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| forumId| Number     | Forum ID

## Freshdesk.createTopic
Create Topic

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| forumId| Number     | Forum ID
| title  | String     | Title of the topic
| message| String     | Message body of the topic
| locked | Boolean    | Set to true if the topic is locked which means that no more posts can be added to the topic
| sticky | Boolean    | Set to true if the topic should stay on top of the forum for additional visibility

## Freshdesk.updateTopic
Update Topic

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| topicId| Number     | Topic ID
| title  | String     | Title of the topic
| message| String     | Message body of the topic
| forumId| Number     | Forum ID
| locked | Boolean    | Set to true if the topic is locked which means that no more posts can be added to the topic
| sticky | Boolean    | Set to true if the topic should stay on top of the forum for additional visibility

## Freshdesk.deleteTopic
Delete Topic

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| topicId| Number     | Topic ID

## Freshdesk.getSingleTopic
Get Topic Info by ID

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| topicId| Number     | Topic ID

## Freshdesk.createComment
Create Comment to Topic

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| topicId| Number     | Topic ID
| body   | String     | Content of the comment in HTML

## Freshdesk.updateComment
Update Forum Comment

| Field    | Type       | Description
|----------|------------|----------
| apiKey   | credentials| Api Key
| domain   | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| commentId| Number     | Topic ID
| body     | String     | Content of the comment in HTML
| answer   | Boolean    | Indicates if the comment is marked as the answer (for forum topics of type 'Question')

## Freshdesk.deleteComment
Delete Forum Comment

| Field    | Type       | Description
|----------|------------|----------
| apiKey   | credentials| Api Key
| domain   | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| commentId| Number     | Topic ID

## Freshdesk.monitorTopic
Start monitoring of topic

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| topicId| Number     | Topic ID
| userId | Number     | ID of the user who wishes to follow the forum topic. If the userId is not mentioned, then the user whose API Key was used to make the API call will be consider the recepient.

## Freshdesk.unMonitorTopic
Stop monitoring of topic

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| topicId| Number     | Topic ID
| userId | Number     | ID of the user who wishes to unfollow the forum topic. If the userId is not mentioned, then the user whose API Key was used to make the API call will be consider the recepient.

## Freshdesk.monitorForum
Start monitoring of forum

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| forumId| Number     | Forum ID
| userId | Number     | ID of the user who wishes to follow the forum. If the userId is not mentioned, then the user whose API Key was used to make the API call will be consider the recepient.

## Freshdesk.unMonitorForum
Stop monitoring of forum

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| forumId| Number     | Forum ID
| userId | Number     | ID of the user who wishes to unfollow the forum. If the userId is not mentioned, then the user whose API Key was used to make the API call will be consider the recepient.

## Freshdesk.getUserMonitoredTopic
Get all Topics the user is following

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| userId | Number     | If the userId is not mentioned, then the user whose API Key was used to make the API call will be consider the recepient.

## Freshdesk.getMonitorStatusForTopic
Get monitoring status for topic

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| userId| Number     | If the userId is not mentioned, then the user whose API Key was used to make the API call will be consider the recepient.

## Freshdesk.getMonitorStatusForForum
Get monitoring status for topic

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| forumId| Number     | Forum ID
| userId | Number     | If the userId is not mentioned, then the user whose API Key was used to make the API call will be consider the recepient.

## Freshdesk.getAllAgents
Get list of all Agents

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com

## Freshdesk.getSingleAgent
Get Agent by ID

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| agentId| Number     | Agent ID

## Freshdesk.getCurrentlyAgent
Get currently authenticated Agent info

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com

## Freshdesk.updateAgent
Update Agent info

| Field      | Type       | Description
|------------|------------|----------
| apiKey     | credentials| Api Key
| domain     | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| agentId    | Number     | Agent ID
| occasional | Boolean    | Set to true if this is an occasional agent (true => occasional, false => full-time)
| signature  | String     | Signature of the agent in HTML format
| ticketScope| Select     | Ticket permission of the agent (1 -> Global Access, 2 -> Group Access, 3 -> Restricted Access). Current logged in agent can't update his/her ticket_scope
| groupIds   | List       | Group IDs associated with the agent. Example: 1,2
| roleIds    | List      | Role IDs associated with the agent. Atleast one role should be assoicated with the agent. Current logged in agent can't update his/her role_ids
| name       | String     | Name of the Agent
| email      | String     | Email address of the Agent.
| phone      | String     | Telephone number of the Agent.
| mobile     | String     | Mobile number of the Agent
| jobTitle   | String     | Job title of the Agent
| language   | String     | Language of the Agent. Default language is 'en'
| timeZone   | String     | Time zone of the Agent. Default value is time zone of the domain

## Freshdesk.getTicketConversations
Get all conversations of ticket

| Field   | Type       | Description
|---------|------------|----------
| apiKey  | credentials| Api Key
| domain  | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| ticketId| Number     | Ticket ID

## Freshdesk.addNoteToTicket
Add Note to Ticket

| Field       | Type       | Description
|-------------|------------|----------
| apiKey      | credentials| Api Key
| domain      | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| ticketId    | Number     | Ticket ID
| body        | String     | Content of the note in HTML
| attachments | List       | List of (url) Attachments associated with the note. The total size of all of a ticket's attachments cannot exceed 15MB.
| incoming    | Boolean    | Set to true if a particular note should appear as being created from outside (i.e., not through web portal). The default value is false
| notifyEmails| List       | List of Email addresses of agents/users who need to be notified about this note
| private     | Boolean    | Set to true if the note is private. The default value is true.
| userId      | Number     | ID of the agent/user who is adding the note

## Freshdesk.createContact
Create Contact

| Field         | Type       | Description
|---------------|------------|----------
| apiKey        | credentials| Api Key
| domain        | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| name          | String     | Name of the contact
| email         | String     | Primary email address of the contact. If you want to associate additional email(s) with this contact, use the other_emails attribute.
| phone         | String     | Telephone number of the contact
| mobile        | String     | Mobile number of the contact
| twitterId     | String     | Twitter handle of the contact
| otherEmails   | List       | Additional emails associated with the contact
| companyId     | Number     | ID of the primary company to which this contact belongs
| viewAllTickets| Boolean    | Set to true if the contact can see all the tickets that are associated with the company to which he belong
| otherCompanies| List       | Additional companies associated with the contact. This attribute can only be set if the Multiple Companies feature is enabled (Estate plan and above)
| address       | String     | Address of the contact.
| customFields  | Array      | Key value pairs containing the name and value of the custom field. Only dates in the format YYYY-MM-DD are accepted as input for custom date fields. Example: 'custom_fields':{'gadget':'Cold Welder'}
| description   | String     | A small description of the contact
| jobTitle      | String     | Job title of the contact
| language      | String     | Language of the contact. Default language is en. This attribute can only be set if the Multiple Language feature is enabled (Garden plan and above)
| tags          | List       | List of tags associated with this contact
| timeZone      | string     | Time zone of the contact. Default value is the time zone of the domain. This attribute can only be set if the Multiple Time Zone feature is enabled (Garden plan and above)

## Freshdesk.updateContact
Update Contact

| Field         | Type       | Description
|---------------|------------|----------
| apiKey        | credentials| Api Key
| domain        | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| contactId     | Number     | Contact ID
| name          | String     | Name of the contact
| email         | String     | Primary email address of the contact. If you want to associate additional email(s) with this contact, use the other_emails attribute.
| phone         | String     | Telephone number of the contact
| mobile        | String     | Mobile number of the contact
| twitterId     | String     | Twitter handle of the contact
| otherEmails   | Array      | Additional emails associated with the contact
| companyId     | Number     | ID of the primary company to which this contact belongs
| viewAllTickets| Boolean    | Set to true if the contact can see all the tickets that are associated with the company to which he belong
| otherCompanies| Array      | Additional companies associated with the contact. This attribute can only be set if the Multiple Companies feature is enabled (Estate plan and above)
| address       | String     | Address of the contact.
| avatar        | File       | Avatar image of the contact The maximum file size is 5MB and the supported file types are .jpg, .jpeg, .jpe, and .png
| customFields  | Array      | Key value pairs containing the name and value of the custom field. Only dates in the format YYYY-MM-DD are accepted as input for custom date fields. Example: 'custom_fields':{'gadget':'Cold Welder'}
| description   | String     | A small description of the contact
| jobTitle      | String     | Job title of the contact
| language      | String     | Language of the contact. Default language is en. This attribute can only be set if the Multiple Language feature is enabled (Garden plan and above)
| tags          | List       | List of tags associated with this contact
| timeZone      | string     | Time zone of the contact. Default value is the time zone of the domain. This attribute can only be set if the Multiple Time Zone feature is enabled (Garden plan and above)

## Freshdesk.updateAvatar
Update Contact Avatar

| Field         | Type       | Description
|---------------|------------|----------
| apiKey        | credentials| Api Key
| domain        | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| contactId     | Number     | Contact ID
| avatar        | File       | Avatar image of the contact The maximum file size is 5MB and the supported file types are .jpg, .jpeg, .jpe, and .png

## Freshdesk.makeAgent
Make Agent from Contact

| Field      | Type            | Description
|------------|-----------------|----------
| apiKey     | credentials     | Api Key
| domain     | credentials     | Domain in freshdesk.com service. Example: your-company.freshdesk.com
| contactId  | Number          | Contact ID
| occasional | boolean         | Set to true if this is an occasional agent (true => occasional, false => full-time)
| signature  | string          | Signature of the agent in HTML format
| ticketScope| Select          | Ticket permission of the agent (1 -> Global Access, 2 -> Group Access, 3 -> Restricted Access). Current logged in agent can't update his/her ticket_scope
| groupIds   | List            | Group IDs associated with the agent
| roleIds    | List            | Role IDs associated with the agent. Atleast one role should be assoicated with the agent. Current logged in agent can't update his/her role_ids

## Freshdesk.deleteContact
Delete Contact

| Field    | Type       | Description
|----------|------------|----------
| apiKey   | credentials| Api Key
| domain   | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| contactId| Number     | Contact ID

## Freshdesk.deleteAgent
Delete Agent. Deleting an agent will downgrade the agent into a contact.

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| agentId| Number     | Agent ID

## Freshdesk.createCompany
Create Company

| Field       | Type       | Description
|-------------|------------|----------
| apiKey      | credentials| Api Key
| domain      | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| name        | String     | Name of the company
| customFields| Array      | Key value pairs containing the names and values of custom fields. Only dates in the format YYYY-MM-DD are accepted as input for custom date fields. Example: 'custom_fields':{'gadget':'Cold Welder'}
| description | String     | Description of the company
| domains     | List       | Domains of the company. Email addresses of contacts that contain this domain will be associated with that company automatically.
| note        | String     | Any specific note about the company

## Freshdesk.updateCompany
Update Company

| Field       | Type       | Description
|-------------|------------|----------
| apiKey      | credentials| Api Key
| domain      | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| companyId   | Number     | Company ID
| name        | String     | Name of the company
| customFields| Array      | Key value pairs containing the names and values of custom fields. Only dates in the format YYYY-MM-DD are accepted as input for custom date fields. Example: 'custom_fields':{'gadget':'Cold Welder'}
| description | String     | Description of the company
| domains     | List       | Domains of the company. Email addresses of contacts that contain this domain will be associated with that company automatically.
| note        | String     | Any specific note about the company

## Freshdesk.getSingleCompany
Get Company info by ID

| Field    | Type       | Description
|----------|------------|----------
| apiKey   | credentials| Api Key
| domain   | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| companyId| Number     | Company ID

## Freshdesk.getAllCompanies
Get list of all Companies

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com

## Freshdesk.deleteCompany
Delete Company

| Field    | Type       | Description
|----------|------------|----------
| apiKey   | credentials| Api Key
| domain   | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| companyId| Number     | Company ID

## Freshdesk.deleteCompanyDomains
Delete all domains from company

| Field    | Type       | Description
|----------|------------|----------
| apiKey   | credentials| Api Key
| domain   | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| companyId| Number     | Company ID

## Freshdesk.createSolutionCategory
Create a Solution Category

| Field           | Type       | Description
|-----------------|------------|----------
| apiKey          | credentials| Api Key
| domain          | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| name            | String     | Unique name of the solution category
| description     | String     | Description of the solution category
| visibleInPortals| List       | List of portal IDs where this category is visible. Allowed only if the account is configured with multiple portals. Example: 1,2,3

## Freshdesk.createTranslatedSolutionCategory
Create a translated solution category to original solution Category ID

| Field           | Type       | Description
|-----------------|------------|----------
| apiKey          | credentials| Api Key
| domain          | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| name            | String     | Unique name of the solution category
| categoryId      | Number     | Original category ID
| language        | String     | Create a translated solution category. (Multilingual Feature must be enabled for the account. Supported languages have to be configured from Admin > General Settings > Helpdesk. Configured languages can be retrieved from Helpdesk Settings)
| description     | String     | Description of the solution category
| visibleInPortals| List       | List of portal IDs where this category is visible. Allowed only if the account is configured with multiple portals. Example: 1,2,3

## Freshdesk.updateSolutionCategory
Update a Solution Category

| Field           | Type       | Description
|-----------------|------------|----------
| apiKey          | credentials| Api Key
| domain          | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| name            | String     | Unique name of the solution category
| description     | String     | Description of the solution category
| visibleInPortals| List      | List of portal IDs where this category is visible. Allowed only if the account is configured with multiple portals. Example: 1,2,3
| language        | String     | Update a translated solution category.

## Freshdesk.getSolutionCategory
Get a Solution Category

| Field     | Type       | Description
|-----------|------------|----------
| apiKey    | credentials| Api Key
| domain    | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| categoryId| Number     | Solution category ID
| language  | String     | Language of category. If category has more then one language

## Freshdesk.getAllSolutionCategories
Get list of all Solution Categories

| Field   | Type       | Description
|---------|------------|----------
| apiKey  | credentials| Api Key
| domain  | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| language| String     | Get all categories with this language translation.

## Freshdesk.deleteSolutionCategory
Delete Solution Category. When deleted, all translated versions will be deleted too.

| Field     | Type       | Description
|-----------|------------|----------
| apiKey    | credentials| Api Key
| domain    | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| categoryId| Number     | Solution Category Id

## Freshdesk.createSolutionFolder
Create Solution Folder in Solution Category

| Field      | Type       | Description
|------------|------------|----------
| apiKey     | credentials| Api Key
| domain     | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| categoryId | Number     | Solution Category Id
| name       | String     | Unique name of the solution folder
| visibility | Select     | Accessibility of this folder. 1 - All Users, 2 - Logged in Users, 3 - Agents, 4 - Selected Companies.
| description| String     | Description of the solution folder
| companyIds | List       | List of IDs of the companies to whom this solution folder is visible. Example: 1,2,3

## Freshdesk.createTranslatedSolutionFolder
Create translated Solution Folder in Solution Category

| Field      | Type       | Description
|------------|------------|----------
| apiKey     | credentials| Api Key
| domain     | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| folderId   | Number     | Solution Folder Id
| name       | String     | Unique name of the solution folder
| language   | String     | Create a translated solution folder.
| description| String     | Description of the solution folder
| companyIds | List       | List of IDs of the companies to whom this solution folder is visible. Example: 1,2,3

## Freshdesk.updateSolutionFolder
Update Solution Folder

| Field      | Type       | Description
|------------|------------|----------
| apiKey     | credentials| Api Key
| domain     | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| folderId   | Number     | Solution Folder Id
| name       | String     | Unique name of the solution folder
| description| String     | Description of the solution folder
| companyIds | List       | List of IDs of the companies to whom this solution folder is visible. Example: 1,2,3
| visibility | Select     | Folder visibility. 1 - All Users, 2 - Logged in Users, 3 - Agents, 4 - Selected Companies.
| language   | String     | Update a translated solution folder

## Freshdesk.getSolutionFolder
Get Solution Folder

| Field   | Type       | Description
|---------|------------|----------
| apiKey  | credentials| Api Key
| domain  | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| folderId| Number     | Solution Folder Id
| language| String     | Get a translated solution folder

## Freshdesk.getAllSolutionFolders
Get all Solution Folder

| Field     | Type       | Description
|-----------|------------|----------
| apiKey    | credentials| Api Key
| domain    | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| categoryId| Number     | Solution Category Id
| language  | String     | Get a translated solution folder

## Freshdesk.deleteSolutionFolder
Delete Solution Folder

| Field   | Type       | Description
|---------|------------|----------
| apiKey  | credentials| Api Key
| domain  | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| folderId| Number     | Solution Folder Id

## Freshdesk.createSolutionArticle
Create Solution Article

| Field          | Type       | Description
|----------------|------------|----------
| apiKey         | credentials| Api Key
| domain         | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| folderId       | Number     | Solution Folder Id
| description    | String     | Description of the solution article
| status         | Select     | Status of the solution article. (1 - draft, 2 - published)
| title          | String     | Title of the solution article
| type           | Number     | The type of the solution article. (1 - permenant, 2 - workaround)
| tags           | List       | List of strings. Tags that have been associated with the solution article
| metaTitle      | String     | Part of SEO-data
| metaDescription| String     | Part of SEO-data
| metaKeywords   | List       | List of keywords. Part of SEO-data

## Freshdesk.createTranslatedSolutionArticle
Create Solution Article

| Field          | Type       | Description
|----------------|------------|----------
| apiKey         | credentials| Api Key
| domain         | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| articleId      | Number     | Solution Article Id
| description    | String     | Description of the solution article
| status         | Select     | Status of the solution article. (1 - draft, 2 - published)
| title          | String     | Title of the solution article
| type           | Number     | The type of the solution article. (1 - permenant, 2 - workaround)
| tags           | List       | List of strings. Tags that have been associated with the solution article
| metaTitle      | String     | Part of SEO-data
| metaDescription| String     | Part of SEO-data
| metaKeywords   | List       | List of keywords. Part of SEO-data
| language       | String     | Create a translated solution article.

## Freshdesk.updateSolutionArticle
Update Solution Article

| Field          | Type            | Description
|----------------|-----------------|----------
| apiKey         | credentials     | Api Key
| domain         | credentials     | Domain in freshdesk.com service. Example: your-company.freshdesk.com
| articleId      | Number          | Solution Article Id
| agentId        | Number          | ID of the agent who created the solution article
| description    | String          | Description of the solution article
| status         | Select          | Status of the solution article. (1 - draft, 2 - published)
| metaTitle      | String          | Part of SEO-data
| metaDescription| String          | Part of SEO-data
| metaKeywords   | List            | List of keywords. Part of SEO-data
| tags           | List            | Tags list that have been associated with the solution article
| title          | String          | Title of the solution article
| type           | Number          | The type of the solution article. (1 - permenant, 2 - workaround)
| language       | String          | Update a translated solution folder

## Freshdesk.getSolutionArticle
Get Solution Article

| Field    | Type       | Description
|----------|------------|----------
| apiKey   | credentials| Api Key
| domain   | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| articleId| Number     | Solution Article Id
| language | String     | Update a translated solution folder

## Freshdesk.getAllSolutionArticles
Get list of all Solution Articles

| Field   | Type       | Description
|---------|------------|----------
| apiKey  | credentials| Api Key
| domain  | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| folderId| Number     | Folder ID
| language| String     | Update a translated solution folder

## Freshdesk.deleteSolutionArticle
Delete the Solution Article

| Field    | Type       | Description
|----------|------------|----------
| apiKey   | credentials| Api Key
| domain   | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| articleId| Number     | article ID

## Freshdesk.createTimeEntry
Create Time entry

| Field        | Type       | Description
|--------------|------------|----------
| apiKey       | credentials| Api Key
| domain       | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| ticketId     | Number     | Ticket ID
| agentId      | Number     | The agent to whom this time-entry is associated. One agent can have only one timer running. Everything else will be stopped if new timer is on for an agent
| billable     | Boolean    | Set as true if the entry is billable. Default value is true
| executedAt   | Datetime   | Time at which this time-entry id added/created
| note         | String     | Description on this time-entry
| startTime    | Datetime   | The time at which the time-entry is added or the time of the last invoked 'start-timer' action using a toggle
| timeSpent    | String     | The number of hours (in hh:mm format). Used to set the total time_spent
| timerRunning | Boolean    | Indicates if the timer is running

## Freshdesk.getAllTimeEntries
Use filters to view only specific time entries (those which match the criteria that you choose). The filters listed in the table below can also be combined.

| Field         | Type       | Description
|---------------|------------|----------
| apiKey        | credentials| Api Key
| domain        | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| companyId     | Number     | Filter by Company ID
| agentId       | Number     | Filter by Agent ID
| executedAfter | String     | Filter by Executed After time
| executedBefore| String     | Filter by Executed Before time
| billable      | Boolean    | Filter by billable

## Freshdesk.getByTicketTimeEntry
Get all Time Entry of current Ticket

| Field   | Type       | Description
|---------|------------|----------
| apiKey  | credentials| Api Key
| domain  | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| ticketId| Number     | Ticket ID

## Freshdesk.updateTimeEntry
Update Time Entry by ID

| Field       | Type       | Description
|-------------|------------|----------
| apiKey      | credentials| Api Key
| domain      | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| timeEntryId | Number     | Time Entry ID
| agentId     | Number     | The agent to whom this time-entry is associated. One agent can have only one timer running. Everything else will be stopped if new timer is on for an agent
| billable    | Boolean    | Set as true if the entry is billable. Default value is true
| executedAt  | String     | Time at which this time-entry id added/created. Format YYYY-MM-DD
| note        | String     | Description on this time-entry
| start_time  | Datetime   | The time at which the time-entry is added or the time of the last invoked 'start-timer' action using a toggle
| timeSpent   | String     | The number of hours (in hh:mm format). Used to set the total time_spent
| timerRunning| Boolean    | Indicates if the timer is running

## Freshdesk.deleteTimeEntry
Delete Time Entry by ID

| Field      | Type       | Description
|------------|------------|----------
| apiKey     | credentials| Api Key
| domain     | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| timeEntryId| Number     | Time Entry ID

## Freshdesk.toggleTimer
Toggle timer

| Field      | Type       | Description
|------------|------------|----------
| apiKey     | credentials| Api Key
| domain     | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| timeEntryId| Number     | Time Entry ID

## Freshdesk.getAllSurveys
Get list of Surveys

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| active| String     | Show all or only active surveys. Example: active (show all active surveys)

## Freshdesk.getAllRoles
Show all roles

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com

## Freshdesk.getSingleRole
Get role by ID

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| roleId| Number     | Role ID

## Freshdesk.getAllGroups
Get all groups

| Field | Type       | Description
|-------|------------|----------
| apiKey| credentials| Api Key
| domain| credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com

## Freshdesk.getGroup
Get group by ID

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| groupId| Number     | Group ID

## Freshdesk.createGroup
Create Group for your agents

| Field           | Type       | Description
|-----------------|------------|----------
| apiKey          | credentials| Api Key
| domain          | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| name            | String     | Unique name of the group
| agentIdList     | List       | Array of agent user ids separated by comma (' , ').
| autoTicketAssign| Boolean    | Describes the automatic ticket assignment type. Will not be supported if the 'Round Robin' feature is disabled for the account. The default value is false
| description     | String     | Description of the group
| escalateTo      | Number     | The user to whom the escalation email is sent of a ticket is unassigned. To create/update escalate_to with 'none' provide the value 'null' in the request
| unassignedFor   | String     | The time after which an escalation email will be sent if a ticket remains unassigned. The accepted values are '30m' for 30 minutes, '1h' for 1 hour, '2h' for 2 hour, '4h' for 4 hour, '8h' for 8 hour, '12h' for 12 hour, '1d' for 1 day, '2d' for 2days, '3d' for 3 days. The default value is '30m'

## Freshdesk.updateGroup
Update group

| Field           | Type       | Description
|-----------------|------------|----------
| apiKey          | credentials| Api Key
| domain          | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| groupId         | Number     | Group ID
| name            | String     | Unique name of the group
| agentIdList     | List       | Array of agent user ids separated by comma (' , ').
| autoTicketAssign| Boolean    | Describes the automatic ticket assignment type. Will not be supported if the 'Round Robin' feature is disabled for the account. The default value is false
| description     | String     | Description of the group
| escalateTo      | Number     | The user to whom the escalation email is sent of a ticket is unassigned. To create/update escalate_to with 'none' provide the value 'null' in the request
| unassignedFor   | String     | The time after which an escalation email will be sent if a ticket remains unassigned. The accepted values are '30m' for 30 minutes, '1h' for 1 hour, '2h' for 2 hour, '4h' for 4 hour, '8h' for 8 hour, '12h' for 12 hour, '1d' for 1 day, '2d' for 2days, '3d' for 3 days. The default value is '30m'

## Freshdesk.deleteGroup
Delete group

| Field  | Type       | Description
|--------|------------|----------
| apiKey | credentials| Api Key
| domain | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| groupId| Number     | Group ID

## Freshdesk.deleteTicket
Delete ticket

| Field   | Type       | Description
|---------|------------|----------
| apiKey  | credentials| Api Key
| domain  | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| ticketId| Number     | Ticket ID

## Freshdesk.restoreTicket
Restore deleted ticket

| Field   | Type       | Description
|---------|------------|----------
| apiKey  | credentials| Api Key
| domain  | credentials| Domain in freshdesk.com service. Example: your-company.freshdesk.com
| ticketId| Number     | Ticket ID

