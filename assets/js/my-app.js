
Vue.component("downloadExcel", JsonExcel);

window.app = new Vue({
    el: '#app',

    data: {
        formSet: {
            "savingSuccessful": false,
            "SuccessfulMessage": null,
        },
        rytmSet: {
            "count": 3,
            "languageList": [],
            "selectedlanguageList": null,
            errorMsg: null,
            starttext: null,
            isBusy: false,
            items: [{
                    title: 'title',
                    content: 'content'
                },

            ],

            fields: [{
                    key: 'actions',
                    label: ''
                }, {
                    key: 'title',
                    label: 'title'
                }, {
                    key: 'content',
                    label: 'content'
                }, {
                    key: 'actionsContent',
                    label: ''
                },

            ]
        },

    },
    methods: {
        saveData(event) {
            event.preventDefault();
            let self = this;
            let data = {};
            data.action = 'rytme_data_save';
            data.security = appm.security;
            data.login = self.formSet.login;
            data.pwsd = self.formSet.pwsd;
            data.fp = self.formSet.fp;
            jQuery.post(appm.ajax_url, data, function (response) {
                self.formSet = response.data;
            });
        },
        validateData(event) {

            event.preventDefault();
            let self = this;
            let data = {};
            data.action = 'validate_data';
            data.security = appm.security;

            console.log(data);
            jQuery.post(appm.ajax_url, data, function (response) {
                //  console.log(response);

                self.formSet = response.data;
                // self.formSet.savingSuccessful = true;
            });
        },
        rytmData(event) {
            let data = {};
            let self = this;
            data.action = 'rytm_data';
            data.security = appm.security;
            data.operation = 'languageList';

            jQuery.post(appm.ajax_url, data, function (response) {
                self.rytmSet.languageList = JSON.parse(response.data).data;
                self.rytmSet.selectedlanguageList = self.rytmSet.languageList.find(o => o.isDefault == true)._id
                    // self.formSet.savingSuccessful = true;
            });

        },

        RefreshTitle(item, index, button) {
            let self = this;
            self.rytmSet.isBusy = !self.rytmSet.isBusy;

            let data = {};
            data.action = 'rytm_data_get_title';
            data.security = appm.security;
            data.languageId = this.rytmSet.selectedlanguageList;
            data.starttext = this.rytmSet.starttext;

            jQuery.post(appm.ajax_url, data, function (response) {
                self.rytmSet.isBusy = !self.rytmSet.isBusy;
                if (response.success) {
                    let tmp = JSON.parse(response.data);

                    if (tmp.success) {
                        item.title = tmp.data.contentSingle

                    } else {
                        self.rytmSet.errorMsg = tmp.data.message;

                    }

                }

            });

        },

        RefreshContent(item, index, button) {
            let self = this;
            self.rytmSet.isBusy = !self.rytmSet.isBusy;

            let data = {};
            data.action = 'rytm_data_get_content';
            data.security = appm.security;
            data.languageId = this.rytmSet.selectedlanguageList;
            data.starttext = this.rytmSet.starttext;
            data.title = item.title;

            jQuery.post(appm.ajax_url, data, function (response) {
                self.rytmSet.isBusy = !self.rytmSet.isBusy;
                if (response.success) {
                    let tmp = JSON.parse(response.data);

                    if (tmp.success) {
                        item.content = tmp.data.content

                    } else {
                        self.rytmSet.errorMsg = tmp.data.message;

                    }

                }

            });

        },

        rytmContentGenerator(event) {
            console.log('rytmContentGenerator');
            console.log(this.rytmSet.items.length);
            let self = this;

            for (let i = self.rytmSet.items.length - 1; i >= 0; i--) {
                setTimeout(function () {
                    console.log(i);
                    console.log(self.rytmSet.items[i].title);

                    self.RefreshContent(self.rytmSet.items[i]);

                }, (this.rytmSet.items.length - i) * 2000);

            }

        },

        rytmStartGenerator(event) {

            let self = this;
            self.rytmSet.errorMsg = '';
            // this.rytmSet.isBusy = !this.rytmSet.isBusy;
            let data = {};
            data.action = 'rytm_data_get_title';
            data.security = appm.security;
            data.languageId = this.rytmSet.selectedlanguageList;
            data.starttext = this.rytmSet.starttext;
            this.rytmSet.items = [];

            for (let i = this.rytmSet.count; i > 0; i--) {
                setTimeout(function () {
                    jQuery.post(appm.ajax_url, data, function (response) {
                        console.log(response.success);
                        if (response.success) {
                            let tmp = JSON.parse(response.data);
                            console.log(tmp);
                            if (tmp.success) {
                                self.rytmSet.items.push({
                                    title: tmp.data.contentSingle,
                                    content: '..............',
                                });
                            } else {
                                self.rytmSet.errorMsg = tmp.data.message;

                            }

                        }

                    });
                }, (this.rytmSet.count - i) * 2000);

            }

        },
        csvExport(arrData) {
		let self = this;
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += [
                Object.keys(self.rytmSet.items[0]).join(";"),
                ...self.rytmSet.items.map(item => Object.values(item).join(";"))
            ]
            .join("\n")
            .replace(/(^\[)|(\]$)/gm, "");

            const data = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", data);
            link.setAttribute("download", "export.csv");
            link.click();

        },

    },

    mounted: function () {
        //this.data.formSet.savingSuccessful = true;
        let self = this;
        jQuery.post(appm.ajax_url, {
            action: 'get_rytme_data',
            security: appm.security
        }, function (response) {
            // console.log(response);

            self.formSet = response.data;
            // self.formSet.savingSuccessful = true;
        });
    }
});
