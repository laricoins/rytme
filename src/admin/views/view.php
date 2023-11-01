<?php
   defined('ABSPATH') || die('');
    $options = RytMeGetOptions();
   
   ?>
<div class="wrap" >
   <div id="app">
      <b-tabs content-class="mt-3" fill>
         <b-tab title="<?php esc_html_e('General tings', 'rytme'); ?>" active>
            <template>
               <b-form @submit="saveData" >
                  <div class="row g-3 align-items-center mb-5">
                     <div class="col-2">
                        <label for="login" class="col-form-label"><?php esc_html_e('Login', 'rytme'); ?></label>
                     </div>
                     <div class="col-auto">
                        <input  id="login" class="form-control" v-model="formSet.login" required  placeholder="<?php esc_html_e('Enter Login', 'rytme'); ?>">
                     </div>
                     <div class="col-auto">
                        <span class="form-text">
                        <?php esc_html_e('Login from  https://rytr.me/ ', 'rytme'); ?>
                        </span>
                     </div>
                  </div>
                  <div class="row g-3 align-items-center mb-5">
                     <div class="col-2">
                        <label for="pwsd" class="col-form-label"><?php esc_html_e('Enter Password', 'rytme'); ?></label>
                     </div>
                     <div class="col-auto">
                        <input  id="pwsd" class="form-control" v-model="formSet.pwsd" required  placeholder="<?php esc_html_e('Enter Password', 'rytme'); ?>">
                     </div>
                  </div>
                  <div class="row g-3 align-items-center mb-5">
                     <div class="col-2">
                        <label for="fp" class="col-form-label"><?php esc_html_e('fp', 'rytme'); ?></label>
                     </div>
                     <div class="col-4">
                        <input  id="fp" class="form-control" v-model="formSet.fp" required  placeholder="<?php esc_html_e('Enter fb', 'rytme'); ?>">
                     </div>
                  </div>
                  <b-alert show dismissible fade v-if="formSet.savingSuccessful">{{ formSet.SuccessfulMessage }} </b-alert>
                  <b-button type="submit" size="lg"  variant="primary"><?php esc_html_e('Save', 'rytme'); ?></b-button>
                  <b-button class="ml-5" @click="validateData" size="lg" variant="info"><?php esc_html_e('Validate Cred', 'rytme'); ?></b-button>
               </b-form>
            </template>
         </b-tab>
         <b-tab @click="rytmData" title="<?php esc_html_e('Bulck text Generator', 'rytme'); ?>">
            <template>
               <div class="row g-3 align-items-center mb-5">
                  <div class="col-auto">
                     <input  id="count" class="form-control" v-model="rytmSet.count" required  placeholder="<?php esc_html_e('Enter count text', 'rytme'); ?>">
                  </div>
                  <div class="col-auto">
                     <input  id="count" class="form-control" v-model="rytmSet.starttext" required  placeholder="<?php esc_html_e('Enter starttext text', 'rytme'); ?>">
                  </div>
                  <div class="col-auto">
                     <b-form-select  v-model="rytmSet.selectedlanguageList">

					 
                        <b-form-select-option v-for="option in rytmSet.languageList" :value="option._id" :selected="option.isDefault" >
                           {{ option.name }}
                        </b-form-select-option>
                     </b-form-select>
                    
                  </div>
				  <div class="col-auto">
                     
					   <b-button class="ml-5" @click="rytmStartGenerator" v-if="rytmSet.selectedlanguageList && rytmSet.starttext"  variant="info"><?php esc_html_e('Get Title', 'rytme'); ?></b-button>
					   <b-alert show variant="danger"  v-if="rytmSet.errorMsg">{{rytmSet.errorMsg}}</b-alert>
                  </div>
				  				  <div class="col-auto">
                     
					   <b-button class="ml-5" @click="rytmContentGenerator" v-if="rytmSet.count == rytmSet.items.length"  variant="primary"><?php esc_html_e('Get Content', 'rytme'); ?></b-button>
					  
                  </div>
				  
               </div>
			   
			    <div class="row g-3 align-items-center mb-5">
				   <b-button class="ml-5" @click="csvExport"   variant="info"><?php esc_html_e('Export To CsV', 'rytme'); ?></b-button>
				
				<!--
					<download-excel :data="rytmSet.items">
					<b-button class="mb-5"><?php esc_html_e('Export To Excel', 'rytme'); ?></b-button>
					</download-excel>
				-->
				
					
					
				<b-table striped hover :items="rytmSet.items"  :fields="rytmSet.fields" :busy="rytmSet.isBusy" >
       <template #cell(name)="row">
        {{ row.value.first }} {{ row.value.last }}
      </template>

      <template #cell(actions)="row">
        <b-button size="sm" @click="RefreshTitle(row.item, row.index, $event.target)" class="mr-1">
         <b-icon icon="arrow-counterclockwise"></b-icon>
        </b-button>
       
      </template>
	  
	        <template #cell(actionsContent)="row">
        <b-button size="sm" @click="RefreshContent(row.item, row.index, $event.target)" class="mr-1">
         <b-icon icon="arrow-counterclockwise"></b-icon>
        </b-button>
       
      </template>
				</b-table>
				</div>
            </template>
         </b-tab>
		 
		  <b-tab  title="<?php esc_html_e('Help', 'rytme'); ?>">
            <template>
			                  <div class="row g-3 align-items-center mb-5">

                     <div class="col-12">
					 <?php esc_html_e('Need help? ==>  https://t.me/ddnitecry', 'rytme'); ?>
                      
                     </div>
                  </div>
			</template>
         </b-tab>
      </b-tabs>
	  
	  
   </div>
</div>
