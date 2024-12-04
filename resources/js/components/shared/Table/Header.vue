<template>
  <thead class="customHeader">
    <tr>
      <th>
        <span v-if="allselectable">
          <b-form-checkbox :id="'checkbox-all'" name="checkbox-all" class="custom-checkbox" @change="toggleSelectAll"
            style="" :checked="selectall" />
        </span>
      </th>
      <th v-for="(column, index) in refreshedColumns" :key="index">{{ column }}</th>
      <th v-if="has_action">&nbsp;</th>
    </tr>
  </thead>
</template>

<style scoped>
  thead.customHeader {
    background: #eff2fe !important;
    height: 51px;
  }



  thead.customHeader tr th span .custom-checkbox ::before {
    border-radius: 4px !important;
    border: 0.50px #5063F4 solid !important;
    padding-left: 2px;
  }

  thead.customHeader tr th span .custom-checkbox .custom-control-label {
    border: 0.50px #5063F4 solid !important;
    margin-top: 0 !important;


  }

  thead .custom-control-label {
    margin-top: 0 !important;
  }

  thead.customHeader tr {
    border-bottom: 0 solid #b3b2b2 !important;
  }

  thead.customHeader tr th {
    color: black;
    font-size: 16px !important;
    font-weight: 700;
    background: inherit !important;
    max-width: 300px;
    /* min-width: 150px; */
    padding-right: 0.75rem;
    padding-left: 0.55rem;
  }

  @media screen and (max-width:1200px) {
    thead.customHeader tr th {
      font-size: .75em;
    }
  }
</style>

<script>
  export default {
    props: ['nonRenderbleColumns', 'columns', 'has_action', 'selectable', 'allselectable', 'select_all', 'data', 'selected_items'],
    data() {
      let displayablecolumns = [];

      if (this.nonRenderbleColumns) {
        displayablecolumns = this.columns.filter((element, index) => {
          return !this.nonRenderbleColumns.includes(element);
        });

      } else {
        displayablecolumns = this.columns;
      }

      return {
        selectall: false,
        refreshedColumns: displayablecolumns
      };
    },
    methods: {
      toggleSelectAll(newValue) {

        if (newValue) {
          this.selectall = true;
        } else {
          this.selectall = false;
        }
        if (newValue == "selectAll") {
          this.$emit("selectAllR", newValue);
        } else {
          this.$emit("selectAllR", newValue);
        }

      }
    },
    watch: {
      select_all(newValue, oldValue) {
        this.selectall = newValue;
        // this.$emit("selectAllRO", newValue);

      },

    }
  };
</script>