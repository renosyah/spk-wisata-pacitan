
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

            // data untuk jarak
            distance: { min : 0, max:0 },

            // data array untuk umur
            ages:[],

            // parameter untuk request
            // data pariwisata
            param : {

                // id kategori yg dipilih
                category_choosed : 0,

                // data request untuk fasilias
                facility : { label : "Fasilitas Wisata", values : [] },

                // data request untuk jarak
                distance : { label : "Jarak", min_value : "10", max_value : "60" },

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
            is_online : true,

            // simple dialog modal
            // untuk memberitahu user apakah
            // ada kesalahan
            modal_warning : {

                //  judul di dialog
                title :"Perhatian",

                // pesan di dialog
                message : "",

                // modal
                modal : null,
            }
        }
    },

    // fungsi yang akan dipanggil saat aplikasi di run
    created(){

        // fungsi yang akan menghandle kondisi saat perangkat offline
        window.addEventListener('offline', () => { this.is_online = false })

        // fungsi yang akan menghandle kondisi saat perangkat online
        window.addEventListener('online', () => { this.is_online = true })

        // set variabel noBackExitsApp ke true
        window.history.pushState({ noBackExitsApp: true }, '')

        // handle event popstate
        window.addEventListener('popstate', this.backPress )

        // memanggil fungsi untuk merequest data
        // yang akan digunakan sebagai opsi pilihan
        // saat user akan menginput data
        this.loadPageData()
    },

    // fungsi yang akan dipanggil saat view di render
    mounted () {

        // isi value modal
        this.modal_warning.modal = window.$('#modal-warning')

        // inisialisasi fungsi dropdown materialize
        // drop down class
        window.$('.dropdown-trigger').dropdown();

        // inisialisasi fungsi modal materialize
        // modal class
        window.$('.modal').modal();
 
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
                    this.distance = response.data.distance;

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

            // validasi jika param fasilitas kosong
            if (this.param.facility.values.length == 0){

                // tampilkan dialog
                this.showWarning("Perhatian","Harap memilih fasilitas wisata minimal satu!")
            
                // hentikan program
                return;
            }

            // alihkan ke halaman loading
            this.switchPage("loading-page")

            let category = "kategori_id=" + this.param.category_choosed
            let facility = this.encodeQueryData("fasilitas[]",this.param.facility.values)
            let min_ticket_price = "min_tiket_masuk=" + this.param.ticket_price.min_value
            let max_ticket_price = "max_tiket_masuk=" + this.param.ticket_price.max_value
            let min_distance = "min_jarak=" + this.param.distance.min_value
            let max_distance = "max_jarak=" + this.param.distance.max_value
            let urlQuery = baseURL + 'api/all_data_pariwisata_spk.php?'+ category +'&'+ facility +'&'+ min_ticket_price +'&'+ max_ticket_price+'&'+min_distance+'&'+max_distance

            // menggunakan library axio
            // untuk melakukan http request
            axios

                // url target
                .get(urlQuery)
                
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

            // validasi jika param fasilitas kosong
            if (this.param.facility.values.length == 0){

                // tampilkan dialog
                this.showWarning("Perhatian","Harap memilih fasilitas wisata minimal satu!")
            
                // hentikan program
                return;
            }

            // alihkan ke halaman loading
            this.switchPage("loading-page")
            
            let category = "kategori_id=" + this.param.category_choosed
            let facility = this.encodeQueryData("fasilitas[]",this.param.facility.values)
            let min_ticket_price = "min_tiket_masuk=" + this.param.ticket_price.min_value
            let max_ticket_price = "max_tiket_masuk=" + this.param.ticket_price.max_value
            let min_distance = "min_jarak=" + this.param.distance.min_value
            let max_distance = "max_jarak=" + this.param.distance.max_value
            let min_age = "min_umur=" + this.param.age.min_value
            let max_age = "max_umur=" + this.param.age.max_value
            let urlQuery = baseURL + 'api/all_data_pariwisata_spk_goa.php?'+ category +'&'+ facility +'&'+ min_ticket_price +'&'+ max_ticket_price+'&'+min_distance+'&'+max_distance+'&'+min_age+'&'+max_age


            // menggunakan library axio
            // untuk melakukan http request
            axios

                // url target
                .get(urlQuery)
                 
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
                facility : { label : "Fasilitas Wisata", values : [] },

                // data request untuk jarak
                distance : { label : "Jarak", min_value : "10", max_value : "60" },

                // data request untuk harga tiket
                ticket_price : { label : "Tiket Masuk", min_value : "", max_value : "" },

                // data request untuk umur
                age : { label : "Pilih Umur", min_value : "", max_value : "" }
            }

            // alihkan ke hamalan kategori
            this.switchPage("category-page")
        },

        // fungsi untuk mengubah array menjadi
        // encode query form
        encodeQueryData(name,values) {

            // siapkan array kosong
            let ret = []

            // iterasi array dari parameter
            for (let d in values){ ret.push(name + '=' + encodeURIComponent(values[d])) }
            
            // kembalikan hasil dengan join &
            return ret.join('&')
         },
        
         // fungsi untuk menampilkan modal warning
        showWarning(title,message){

            // isi title modal
            this.modal_warning.title = title

            // isi pesan
            this.modal_warning.message = message

            // panggil fungsi open
            this.modal_warning.modal.modal('open')
        },

        
        // fungsi yang akan mengembalikan user
        // ke halaman sebelum halaman yg sedang
        // dikunjungi user sekarang
        backPress(){

            // jika state yg diberikan adalah tidak boleh
            // keluar dari aplikasi
            if (event.state && event.state.noBackExitsApp) {

                // panggil fungsi push state
                // agar tidak keluar aplikasi saat tombol kembali ditekan
                window.history.pushState({ noBackExitsApp: true }, '')
            } 

            // check nama halaman yg sekarang
            switch(this.page.name) {

                // jika hamalan kriteria maka kembali ke halaman kategori
                case "criteria-page": this.switchPage("category-page"); break;

                // jika hamalan hasil maka kembali ke halaman kriteria
                case "result-page": this.switchPage("criteria-page"); break;

                // jika hamalan detail maka kembali ke halaman hasil
                case "detail-page": this.switchPage("result-page"); break;
                default: break;
            }
        }

    }
})
