
// url dasar yang akan diakses
const baseURL = "http://localhost:80/"

// membuat object vue
new Vue({

    // tempelkan ke element html dengan id app
    el: '#app',

    // membuat fungsi data
    data() {

        // yang akan mengembalikan object
        return {

            // object untuk menentukan 
            // user sedang berada di halaman mana
            page : { name : "loading-page" },

            // data array untuk kategori
            categories:[],

            // data array untuk fasilitas
            facilities:[],

            // data array untuk harga tiket
            ticket_prices:[],

            // data array untuk jarak
            distances:[],

            // data array untuk umur
            ages:[],

            // parameter untuk request
            // data pariwisata
            param : {

                // id kategori yg dipilih
                category_choosed : 0,

                // data request untuk fasilias
                facility : { label : "Fasilitas Wisata", value : "" },

                // data request untuk jarak
                distance : { label : "Jarak", min_value : "", max_value : "" },

                // data request untuk harga tiket
                ticket_price : { label : "Tiket Masuk", min_value : "", max_value : "" },

                // data request untuk umur
                age : { label : "Pilih Umur", min_value : "", max_value : "" }
            },

            // data array untuk menampung 
            // respon data pariwisata
            results : [],

            // data detail untuk menampung 
            // respon detail data pariwisata
            detail : { id : 0, kategori_id : 0,  nama : "", lokasi : "",  jarak : 0, deskripsi : "" },

            // status untuk menunjukan
            // apakah perangkat offline
            // atau sedang online
            is_online : true
        }
    },

    // fungsi yang akan dipanggil saat aplikasi di run
    created(){

        // fungsi yang akan menghandle kondisi saat perangkat offline
        window.addEventListener('offline', () => { this.is_online = false })

        // fungsi yang akan menghandle kondisi saat perangkat online
        window.addEventListener('online', () => { this.is_online = true })

        // memanggil fungsi untuk merequest data
        // yang akan digunakan sebagai opsi pilihan
        // saat user akan menginput data
        this.loadPageData()
    },

    // fungsi yang akan dipanggil saat view di render
    mounted () {

        // inisialisasi fungsi dropdown materialize
        // drop down class
        window.$('.dropdown-trigger').dropdown();
    },

    // kumpulan fungsi2 yang akan digunakan
    // dalam instance ini
    methods : {

        // yang akan dipanggil saat ingin mengganti tampilan page
        switchPage(name){ this.page.name = name },

        // yang akan dipanggil saat kategori telah dipilih
        // dan akan dialihkan ke halaman kriteria
        chooseCategory(value){ this.param.category_choosed = value; this.switchPage("criteria-page")  },
        
        // yang akan dipanggil saat ingin merequest opsi pilihan
        // yg akan digunakan saat user akan menginput data
        loadPageData(){

            // alihkan ke halaman loading
            this.switchPage("loading-page")

            // menggunakan library axio
            // untuk melakukan http request
            axios

                // url target
                .get(baseURL + 'data.json')

                // saat response didapat
                .then(response => {
                    
                    // isi data array kategori
                    this.categories = response.data.categories;

                    // isi data array fasilitas
                    this.facilities = response.data.facilities ;

                    // isi data array harga tiket
                    this.ticket_prices = response.data.ticket_prices;

                    // isi data array jarak
                    this.distances = response.data.distances;

                    // isi data array umur
                    this.ages = response.data.ages;

                    // alihkan ke halaman kategori
                    this.switchPage("category-page")
                })

                // saat error didapat
                .catch(errors => {

                    // tampilkan log
                    console.log(errors)

                    // alihkan ke halaman kategori
                    this.switchPage("category-page")
                }) 
        },

        // fungsi yang akan dipanggil saat
        // merequest data pariwisata
        // untuk kategori non-goa
        getSAWResult(){

            // alihkan ke halaman loading
            this.switchPage("loading-page")

            let category = "kategori_id=" + this.param.category_choosed
            let facility = "fasilitas_id=" + this.param.facility.value
            let min_ticket_price = "min_tiket_masuk=" + this.param.ticket_price.min_value
            let max_ticket_price = "max_tiket_masuk=" + this.param.ticket_price.max_value
            let min_distance = "min_jarak=" + this.param.distance.min_value
            let max_distance = "max_jarak=" + this.param.distance.max_value

            // menggunakan library axio
            // untuk melakukan http request
            axios

                // url target
                .get(baseURL + 'api/all_data_pariwisata_spk.php?'+ category +'&'+ facility +'&'+ min_ticket_price +'&'+ max_ticket_price+'&'+min_distance+'&'+max_distance)
                
                // saat response didapat
                .then(response => {
                    
                    this.results = response.data.data
                    this.switchPage("result-page")
                })

                // saat error didapat
                .catch(errors => {

                    console.log(errors)
                    this.switchPage("result-page")
                })  
        },

        // fungsi yang akan dipanggil saat
        // merequest data pariwisata
        // untuk kategori goa
        getSAWResultGoa(){

            // alihkan ke halaman loading
            this.switchPage("loading-page")
            
            let category = "kategori_id=" + this.param.category_choosed
            let facility = "fasilitas_id=" + this.param.facility.value
            let min_ticket_price = "min_tiket_masuk=" + this.param.ticket_price.min_value
            let max_ticket_price = "max_tiket_masuk=" + this.param.ticket_price.max_value
            let min_distance = "min_jarak=" + this.param.distance.min_value
            let max_distance = "max_jarak=" + this.param.distance.max_value
            let min_age = "min_umur=" + this.param.age.min_value
            let max_age = "max_umur=" + this.param.age.max_value

            // menggunakan library axio
            // untuk melakukan http request
            axios

                // url target
                .get(baseURL + 'api/all_data_pariwisata_spk_goa.php?'+ category +'&'+ facility +'&'+ min_ticket_price +'&'+ max_ticket_price+'&'+min_distance+'&'+max_distance+'&'+min_age+'&'+max_age)
                 
                // saat response didapat               
                .then(response => {
                    
                    this.results = response.data.data
                    this.switchPage("result-page")
                })

                // saat error didapat
                .catch(errors => {

                    console.log(errors)
                    this.switchPage("result-page")
                })  
        },
        getDetail(id){
            
            // alihkan ke halaman loading
            this.switchPage("loading-page")

            // menggunakan library axio
            // untuk melakukan http request
            axios

                // url target
                .get(baseURL + 'api/one_data_pariwisata.php?id=' + id)
                
                // saat response didapat
                .then(response => {
                    
                    this.detail = response.data.data
                    this.switchPage("detail-page")
                })

                // saat error didapat
                .catch(errors => {

                    console.log(errors)
                    this.switchPage("detail-page")
                }) 

        },

        // fungsi untuk kembali ke halaman
        // utama dan mereset data parameter request
        toHome(){
            this.param = {

                // id kategori yg dipilih
                category_choosed : 0,

                // data request untuk fasilias
                facility : { label : "Fasilitas Wisata", value : "" },

                // data request untuk jarak
                distance : { label : "Jarak", min_value : "", max_value : "" },

                // data request untuk harga tiket
                ticket_price : { label : "Tiket Masuk", min_value : "", max_value : "" },

                // data request untuk umur
                age : { label : "Pilih Umur", min_value : "", max_value : "" }
            }

            // alihkan ke hamalan kategori
            this.switchPage("category-page")
        }

    }
})
