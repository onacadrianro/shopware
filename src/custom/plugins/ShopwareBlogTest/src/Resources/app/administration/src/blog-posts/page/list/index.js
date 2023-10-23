import template from './list.twig';

const { Component, Mixin } = Shopware;
const Criteria = Shopware.Data.Criteria;

Component.register('blog-posts-list', {
    template,
    inject: ['repositoryFactory'],
    mixins: [
        Mixin.getByName('listing'),
    ],

    data() {
        return {
            blogPosts: null,
            isLoading: true,
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(),
        };
    },

    created() {
        this.getList();
    },

    computed: {
        blogPostsRepository() {
            return this.repositoryFactory.create('blog_posts');
        },

        //columns in page

        columns() {
            return [
                {
                    property: 'title',
                    dataIndex: 'title',
                    label: this.$tc('blog-posts.list.table.title'),
                    routerLink: 'blog.module.detail',
                    primary: true,
                    inlineEdit: 'string',
                },
                {
                    property: 'description',
                    label: this.$tc('blog-posts.list.table.description'),
                    inlineEdit: false,
                },

            ];
        },
    },

    methods: {

        getList() {
            this.isLoading = true;
            const criteria = new Criteria(this.page, this.limit);
            criteria.addSorting(Criteria.sort('updatedAt', 'DESC', false));

            return this.blogPostsRepository.search(criteria, Shopware.Context.api).then((result) => {
                this.blogPosts = result;
            });
        },
    },
});
