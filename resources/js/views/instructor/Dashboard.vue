<template>
		<v-container class="pa-5">
            <h1 class="subheading black--text mb-5">Instructor Dashboard</h1>

			<v-layout row wrap>
				<v-flex  xs12 sm4 lg4>
					<v-card class="ma-3 d-flex justify-space-between pa-5 "  color="error" >

						<div class="white--text">
							<!-- <h2>{{ metrics.courses ? metrics.courses : '...'  }} Courses</h2> -->
							<h2>{{ metrics.courses }} Courses</h2>
							<span>In Total</span>
						</div>

						<!-- <v-icon style="opacity: 0.1;" size="80" color="black">mdi-laptop-account</v-icon> -->
                        <!-- <i  class="icon fa fa-chalkboard"></i> -->
					</v-card>
				</v-flex>
				<v-flex  xs12 sm4 lg4>
					<v-card class="ma-3 d-flex justify-space-between pa-5 "  color="info" >

						<div class="white--text">
							<h2>{{ metrics.sections }} Sections</h2>
							<span>In total</span>
						</div>

						<!-- <v-icon style="opacity: 0.1;" size="100" color="black">mdi-laptop-account</v-icon> -->
                        <!-- <i  class="icon fas fa-bullhorn"></i> -->
					</v-card>
				</v-flex>
				<v-flex  xs12 sm4 lg4>
					<v-card class="ma-3 d-flex justify-space-between pa-5 "  color="success" >

						<div class="white--text">
							<h2>{{ metrics.lectures }} Lectures</h2>
							<span>In total</span>
						</div>

					</v-card>
				</v-flex>
			</v-layout>
		</v-container>
</template>

<script>
import { mapGetters, mapActions } from "vuex";




export default {
	name: 'Instructor-Dashboard',
	components: {

	},
	data: () => ({
        metrics : {
        }
	}),

    created(){
        this.GetMetrics().then((response) => {
            // console.log(response);
            this.metrics = response.data.metrics
        }, (error) => {
            console.log(error);
        })
    },

    methods:{
        ...mapActions(["GetMetrics"]),

    }
}
</script>

<style scoped>
    .icon{
        font-size: 80px;
        opacity: 0.1;
        color: black;
    }
</style>
