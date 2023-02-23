<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>Test-App</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/index.css') }}" rel="stylesheet">
        <link href="{{ asset('css/jquery.dataTables.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="mainBody">
            <nav class="navbar navbar-expand-lg bg-danger">
                <div class="container-fluid">
                    <a class="navbar-brand text-white" href="#">Test-App</a>
                </div>
            </nav>
            <div class="container">
                <div class="row mt-2 mb-2">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <form class="row g-3" v-on:submit.prevent="submit">
                                    <div class="col-md-3">
                                        <label>Company Symbol
                                            <div class="spinner-border spinner-border-sm" role="status" v-if="btnLoader.getCompanies">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </label>
                                        <select class="selectpicker" data-live-search="true" data-width="100%" data-size="12" v-model="selectedSymbol">
                                            <option v-for="symbol in symbols" :value="symbol">@{{ symbol.symbol }}</option>
                                        </select>
                                        <div class="error" v-if="errors['symbol'] != null">@{{ errors['symbol'] }}</div>
                                    </div>
                                    <template>
                                        <div class="col-md-2">
                                            <label>Start Date</label><br/>
                                            <date-picker v-model="formData.start_date" type="date" :disabled-date="disableDays" format="YYYY-MM-DD" value-type="format"></date-picker>
                                            <div class="error" v-if="errors['start_date'] != null">@{{ errors['start_date'] }}</div>
                                        </div>
                                        <div class="col-md-2">
                                            <label>End Date</label><br/> 
                                            <date-picker v-model="formData.end_date" type="date" :disabled-date="disableDays" format="YYYY-MM-DD" value-type="format"></date-picker>
                                            <div class="error" v-if="errors['end_date'] != null">@{{ errors['end_date'] }}</div>
                                        </div>
                                    </template>
                                    <div class="col-md-3">
                                        <label>Email</label>
                                        <input type="text" class="form-control" placeholder="Email" autocomplete="off" v-model="formData.email">
                                        <div class="error" v-if="errors['email'] != null">@{{ errors['email'] }}</div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success mt-4" trpe="submit" :disabled="btnLoader.submit">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table pt-3" id="datatable">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Open</th>
                            <th scope="col">High</th>
                            <th scope="col">Low</th>
                            <th scope="col">Close</th>
                            <th scope="col">Volume</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="text-center">
                            <div class="spinner-border mt-6" role="status" v-if="btnLoader.submit"> 
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <tr v-for="(data,index) in tableData" :key="index">
                            <td>@{{ convertDate(data.date) }}</td>  
                            <td>@{{ data.open }}</td>  
                            <td>@{{ data.high }}</td>  
                            <td>@{{ data.low }}</td>  
                            <td>@{{ data.close }}</td>   
                            <td>@{{ data.volume }}</td>  
                        </tr>
                    </tbody>
                </table>
                <div class="col-md-12">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </body>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/index.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/Chart.bundle.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    v = new Vue({
        el: '#mainBody',
        data: 
        {
            symbols:[],
            selectedSymbol: '',
            formData:{},
            btnLoader:{
                getCompanies:false,
                submit:false
            },
            tableData:[],
            errors:[]
        },
        mounted(){
            this.getCompanies();
            this.refreshSelectpicker();
            $('#datatable').DataTable();
        },
        methods:
        {
            disableDays(date) {
                let startFromDate = moment();
                return date >= startFromDate;
            },
            
            validateData()
            {
                this.errors = [];
                let regexEmail = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/; // Default regex for input type email

                var requiredFields = [
                    'symbol',
                    'start_date',
                    'end_date',
                    'email'
                ];

                requiredFields.forEach((value, index) => {
                    if(this.formData[value] == null || this.formData[value] == '') {
                        var fieldName = value.charAt(0).toUpperCase() + value.slice(1);
                        fieldName = fieldName.replaceAll("_"," ");
                        this.errors[value] = fieldName +' is required';
                    }
                });

                if(this.formData.start_date && moment(this.formData.start_date) > moment(this.formData.end_date))
                {
                    this.errors['start_date'] = "The start date field must be a date before or equal to end date."
                }

                if(this.formData.end_date && moment(this.formData.end_date) < moment(this.formData.start_date))
                {
                    this.errors['end_date'] = "The end date field must be a date after or equal to start date."
                }

                if(this.formData.email && !this.formData.email.match(regexEmail)){ 
                    this.errors['email'] = "This Email is not valid."
                }

            },
            getCompanies(){
                this.btnLoader.getCompanies = true;
                $.ajax("{{ url('/get-companies') }}",{
                    type: "GET",
                    dataType: 'json',
                    data: {},
                    success: (response) =>
                    {
                        this.symbols = response;
                        this.refreshSelectpicker();
                        this.btnLoader.getCompanies = false;
                    },
                    error: (xhr) =>
                    {
                        this.btnLoader.getCompanies = false;
                    }
                });
            },

            refreshSelectpicker(){
                this.$nextTick(() => {
                    $('.selectpicker').selectpicker('refresh');
                });
            },

            convertDate(date){
                return moment.unix(date).format('YYYY-MM-DD');
            },

            submit(){

                this.formData.symbol = this.selectedSymbol.symbol;
                // validate data
                this.validateData()

                this.formData.company_name = this.selectedSymbol.name;

                // If Any errors, return those
                if(Object.keys(this.errors).length > 0)
                {
                    return 0;
                }
                // Loading Animation
                this.btnLoader.submit = true;

                // submitting data to server
                $.ajax("{{'/send-receive-historical-quotes'}}",{
                    type: "POST",
                    dataType: 'json',
                    data: this.formData,
                    success: (response) =>
                    {
                        this.tableData = response.prices;
                        $('#datatable').DataTable().destroy();
                        setTimeout(() => {
                            $('#datatable').DataTable().draw();
                        }, 200);

                        if(window.myChart instanceof Chart){ // destroy and rebuild chart again if already initialized
                            window.myChart.destroy();
                        }
                        var allDates = [];
                        var openRates = [];
                        var closeRates = [];
                        this.tableData.forEach((value, index) => {
                            allDates.push(moment.unix(value.date).format('YYYY-MM-DD'));
                            openRates.push(value.open);
                            closeRates.push(value.close);
                        });
                        this.drawChart(allDates, openRates, closeRates);
                        this.btnLoader.submit = false;
                        
                    },
                    error: (xhr) =>
                    {
                        this.errors = [];
                        this.btnLoader.submit = false;
                        Object.keys(xhr.responseJSON.errors).forEach(key => {
                            this.errors[key] = xhr.responseJSON.errors[key][0];
                        });
                    }
                });
            },

            drawChart(allDates, openRates, closeRates){
                var ctx = document.getElementById("myChart").getContext('2d');
                window.myChart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: allDates,
                        datasets: [
                            {
                                label: "Open",
                                backgroundColor: "blue",
                                data: openRates
                            },
                            {
                                label: "Close",
                                backgroundColor: "red",
                                data: closeRates
                            }
                        ]
                    },
                    options: {
                        legend: {display: false},
                        title: {
                            display: true,
                            text: "Open and Close Priced By Date. Blue indicates open price, Red indicates close price"
                        }
                    }
                });

            }
        }
    });
</script>
</html>
