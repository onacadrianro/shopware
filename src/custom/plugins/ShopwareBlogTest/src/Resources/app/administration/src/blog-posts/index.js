const { Module } = Shopware;

Module.register('blog-posts', {
    type: 'plugin',
    name: 'Blog',
    title: 'blog-posts.general.mainMenuItemGeneral',
    description: 'blog-posts.general.descriptionTextModule',
    color: '#F965AF',
    icon: 'regular-content',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB,
    },

    routes: {
        index: {
            components: {
                default: 'blog-posts-list',
            },
            path: 'index',
        },
        create: {
            components: {
                default: 'blog-posts-create',
            },
            path: 'create',
        },
        detail: {
            component: 'blog-posts-detail',
            path: 'detail/:id',
        },
    },

    navigation: [
        {
            id: 'blog-posts',
            label: 'blog-posts.general.mainMenuItemGeneral',
            path: 'blog-posts.index',
            parent: 'content', //Content TAB
        },
    ],
});
