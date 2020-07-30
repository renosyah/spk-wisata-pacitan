const baseURL = "http://localhost:80/"
new Vue({
    el: '#app',
    data() {
        return {
            page : {
                name : "loading-page",
            },
            categories:[],
            facilities:[],
            ticket_prices:[],
            distances:[],
            ages:[],
            param : {
                category_choosed : 0,
                facility : {
                    label : "Fasilitas Wisata",
                    value : "",
                },
                distance : {
                    label : "Jarak",
                    min_value : "",
                    max_value : ""
                },
                ticket_price : {
                    label : "Tiket Masuk",
                    min_value : "",
                    max_value : ""
                },
                age : {
                    label : "Pilih Umur",
                    min_value : "",
                    max_value : ""
                }
            },
            results : [],
            detail : {
                id : 0,
                kategori_id : 0,
                nama : "",
                lokasi : "",
                jarak : 0,
                deskripsi : "",
            },
            is_online : true
        }
    },    
    created() {
        window.addEventListener('offline', () => {
            this.is_online = false
        })
        window.addEventListener('online', () => {
            this.is_online = true
        })
    },
    created(){
        this.loadPageData()
    },
    mounted () {
        window.$('.dropdown-trigger').dropdown();
    },
    methods : {
        switchPage(name){
            this.page.name = name
        },
        chooseCategory(value){
            this.param.category_choosed = value
            this.switchPage("criteria-page")
        },
        loadPageData(){

            this.switchPage("loading-page")

            axios
                .get(baseURL + 'data.json')
                .then(response => {
                    
                    this.categories = response.data.categories;
                    this.facilities = response.data.facilities ;
                    this.ticket_prices = response.data.ticket_prices;
                    this.distances = response.data.distances;
                    this.ages = response.data.ages;
                    this.switchPage("category-page")
                })
                .catch(errors => {

                    console.log(errors)
                    this.switchPage("category-page")
                }) 
        },
        getSAWResult(){

            this.switchPage("loading-page")

            let category = "kategori_id=" + this.param.category_choosed
            let facility = "fasilitas_id=" + this.param.facility.value
            let min_ticket_price = "min_tiket_masuk=" + this.param.ticket_price.min_value
            let max_ticket_price = "max_tiket_masuk=" + this.param.ticket_price.max_value
            let min_distance = "min_jarak=" + this.param.distance.min_value
            let max_distance = "max_jarak=" + this.param.distance.max_value

            axios
                .get(baseURL + 'api/all_data_pariwisata_spk.php?'+ category +'&'+ facility +'&'+ min_ticket_price +'&'+ max_ticket_price+'&'+min_distance+'&'+max_distance)
                .then(response => {
                    
                    this.results = response.data.data
                    this.switchPage("result-page")
                })
                .catch(errors => {

                    console.log(errors)
                    this.switchPage("result-page")
                })  
        },
        getSAWResultGoa(){

            this.switchPage("loading-page")
            let category = "kategori_id=" + this.param.category_choosed
            let facility = "fasilitas_id=" + this.param.facility.value
            let min_ticket_price = "min_tiket_masuk=" + this.param.ticket_price.min_value
            let max_ticket_price = "max_tiket_masuk=" + this.param.ticket_price.max_value
            let min_distance = "min_jarak=" + this.param.distance.min_value
            let max_distance = "max_jarak=" + this.param.distance.max_value
            let min_age = "min_umur=" + this.param.age.min_value
            let max_age = "max_umur=" + this.param.age.max_value

            axios
                .get(baseURL + 'api/all_data_pariwisata_spk_goa.php?'+ category +'&'+ facility +'&'+ min_ticket_price +'&'+ max_ticket_price+'&'+min_distance+'&'+max_distance+'&'+min_age+'&'+max_age)
                .then(response => {
                    
                    this.results = response.data.data
                    this.switchPage("result-page")
                })
                .catch(errors => {

                    console.log(errors)
                    this.switchPage("result-page")
                })  
        },
        getDetail(id){
            
            this.switchPage("loading-page")
            axios
                .get(baseURL + 'api/one_data_pariwisata.php?id=' + id)
                .then(response => {
                    
                    this.detail = response.data.data
                    this.switchPage("detail-page")
                })
                .catch(errors => {

                    console.log(errors)
                    this.switchPage("detail-page")
                }) 

        },
        toHome(){
            this.param = {
                category_choosed : 0,
                facility : {
                    label : "Fasilitas Wisata",
                    value : "",
                },
                distance : {
                    label : "Jarak",
                    min_value : "",
                    max_value : ""
                },
                ticket_price : {
                    label : "Tiket Masuk",
                    min_value : "",
                    max_value : ""
                },
                age : {
                    label : "Pilih Umur",
                    min_value : "",
                    max_value : ""
                }
            }
            this.switchPage("category-page")
        },
        onBackPress(){
            
            if (this.page.name == "detail-page"){
                this.switchPage("result-page")
            } else if (this.page.name == "result-page"){
                this.switchPage("criteria-page")
            } else if (this.page.name == "criteria-page"){
                this.switchPage("category-page")
            }
        },
    }
})
