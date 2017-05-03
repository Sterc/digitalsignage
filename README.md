# README #
Make sure this file is not deployed by DeployHQ.

### Project details ###

|                       |                                                                    |
|-----------------------|--------------------------------------------------------------------|
| **Summary**           | Write a summary about this project here.                           |
| **Production**        | https://www.example.com     / https://www.example.com/manager      |
| **Staging**           | https://staging.example.com / https://staging.example.com/manager  |
| **Project Manager**   | John Doe                                                           |
| **Lead Developer**    | John Doe                                                           |
| **Developer**         | John Doe                                                           |
| **JIRA Board**        | https://stercbv.atlassian.net                                      |
| **Functional Design** | https://drive.google.com                                           |
| **Technical Design**  | https://drive.google.com                                           |
| **Document name ??**  | Document link                                                      |
| **Production branch** | master                                                             |
| **Development branch**| development                                                        |

### IP Addresses ###

| Description                               | IP Address |
|-------------------------------------------|------------|
| Describe the IP. For example a Client IP. | 0.0.0.0    |
| Describe the IP.                          | 0.0.0.0    |

### MODX User Groups ###

| Name            | Description                          |
|-----------------|--------------------------------------|
| Group A         | Group description                    |
| Group B         | Group description                    |

### Cronjobs ###

| Description                | Schedule                 | Command                                            |
|----------------------------|--------------------------|----------------------------------------------------|
| Describe the cronjob here. | How often should it run. | Enter the full command to execute the script here. |
| Describe the cronjob here. | How often should it run. | Enter the full command to execute the script here. |

### Going live checklist ###
Before going live with this project please check the following things:

* Enter the description here.
* Enter the description here.
* Enter the description here.

### Test checklist ###
Once you deploy a new version of this project please test the following important features of the website:

* Enter the description here.
* Enter the description here.
* Enter the description here.
  
### Project rules ###
* Make use of the PSR Coding Standards combined with the Sterc Coding Standards
* Make use of the Sterc Ruleset within the PHPStorm Codesniffer
* Document all code blocks with PHPDoc
* Every feature / bug requires a new branch which will be merged by a pull request, which is reviewed by another team member
* Changes to categories, templates, chunks, snippets and plugins must be done to the offline database, to make deployments more easy

### Environment ###
* PHP / HHVM
    - Define all PHP modules that differ from the default ones that MODX requires, for example: php_memcache
* NGINX
    - Define all extra settings that NGINX require
* MySQL
* For example: Memcache or Elasticsearch

### Deployments ###

**Files can be deployed from here:**  
https://sterc.deployhq.com/projects/project-name/deployments/new

**Database tables that can be migrated from offline to online if needed:**  
1. modx_categories  
2. modx_categories_closure  
3. modx_minify_files  
4. modx_minify_groups  
5. modx_site_htmlsnippets  
6. modx_site_plugin_events  
7. modx_site_plugins  
8. modx_site_snippets  
9. modx_site_templates  
10. modx_site_tmplvar_access  
11. modx_site_tmplvar_templates  
12. modx_site_tmplvars  

### Classes, Snippets & Plugins ###
Describe all classes, snippets and plugins that have complex functionality.

**Site.class.php**  
Describe the functionality that lives within the Site class.
The Site.class.php is triggered by the Site plugin.

**Example snippet**  
Describe the snippet.

**Example plugin**  
Describe the plugin.

### Libraries and dependencies ###
Describe all custom libraries and dependencies this project uses.

**Monolog**  
Describe where monolog is used and for what purpose.

**Another library**  
Describe the library, where the files are and where it is used.

**Another dependency**  
Describe the dependency, where the files are and where it is used.

### 3th Party connections and API's  ###
Describe all 3th Party connections, API's and code here.

**Google Maps**  
Describe the Google Maps connection.

**Twitter API**  
Describe the Twitter API.

**Another example**  
Describe the example. For example: where are the credentials stored, endpoints / urls or documentation links.

### Update notes  ###
Read this before updating this project.

**Custom Manager Theme**  
This project makes use of a custom manager theme, please restore it after updating.

**Another note**  
Describe the note here.