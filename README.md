# Medium
OctoberCMS Free Medium plugin

Medium plugin documentation

**SDK**

Inside plugin you will see sdk folder that contains HttpClient based on GuzzleHttpClient and the MediumApi. SDK serves as single point of communication with Medium API and RSS feed and all HTTP methods are inside SDK. Hopefully we have written good SDK to the point it can be reused on any other PHP project without much trouble.

**Assets**

This folder only contains css and js used by the plugin. Inside assets you will find Toastr library which is used in backend for info messages when you for example pull articles and you get either Success or Error as result.

**Lang**

At this moment lang only have english version. We haven't worked much on translation of the plugin at this point. In future we will try to translate on other languages and your help would be greatly appreciated if you can translate in your native language.

**Models**

We have 3 models at this point. Article, Author and MediumSettings.
MediumSettings is used for plugin configuration and for now only self issued token from Medium can be store inside. As plugin grows more configurations will come in play.
Article contains all articles pulled from Medium RSS feed. This model has relation with Author.
Author contains all authors or publications you pulled articles from. This model has relation with Article. We have created component for Author listing which some of you might find useful.

**Controllers**

Only two controllers available at this moment and those are Articles and Settings. 
Settings controller has set in place Medium authorization with use of self issued token which you can get on Medium. It is not required for article feed but in future when we add article creation inside plugin you will need to get token and authorize plugin to use that and possibly other features.
Articles controller has list of already pulled articles which you can preview in backend as well and separate page for getting articles by author or publication name, in case you already have articles for some author you can just refresh articles based on author.

**Components**

We have created 3 components for you. Article list, Article single and Author list.
We haven't put much effort in component design because we believe everyone would like to have their design set in place but use it as an example how to use components and what data can be displayed. 
Article list displays articles you have saved. Use defined properties to filter them how you like. Note that you will see Author property which can be used to filter out only articles by given author or you can leave it empty if you want to display all articles regardless of author name.
Article single is displaying single article with all data it has.
Author list will get you all authors you pulled articles from. Authors can be publications as well.
All components have dev property that will just print_r data to help you see what data is passed to the component by simply checking dev property.

**This plugin is free and will stay free**. Feel free to write your opinion and check github if you want to look at the code or make pull requests.

Thank you all for reading through documentation. If you think we missed something contact us and we will fix it.

You can find plugin on October cms store right here: https://octobercms.com/plugin/ideaverum-medium

For all questions or suggestions contact us at info@ideaverum.hr or visit https://ideaverum.hr/en/contact and leave a message
