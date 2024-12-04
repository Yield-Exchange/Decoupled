// campaignModule.js
import * as types from "./mutation-types.js";

const campaignModule = {
    namespaced: true,
    state: {
        selectedProducts: [],
        selectedProductsids: [],
        selectAllProducts: false,
        selectedProductsMaximums: [],
        selectedProductsMinimums: [],
        selectedProductsrates: [],
        selectedProductsTermLengths: [],
        selectedProductsNames: [],
        activeProductList: [],
    },
    mutations: {
        [types.SET_ACTIVE_PRODUCT_LIST](state, products) {
            state.activeProductList = products;
        },
        [types.UPDATE_SELECTED_PRODUCT_ENTRY](state, entry) {
            const product_id = entry.product_id;
            const existingProductIndex = state.selectedProducts.findIndex(
                (p) => p.product_id === product_id
            );

            switch (entry.field) {
                case "name":
                    console.log(entry, "field is name");
                    state.selectedProducts[existingProductIndex].product_name =
                        entry.value;
                    break;
                case "maximum":
                    state.selectedProducts[existingProductIndex].maximum =
                        entry.value;
                    break;
                case "minimum":
                    state.selectedProducts[existingProductIndex].minimum =
                        entry.value;
                    break;
                case "rate":
                    state.selectedProducts[existingProductIndex].rate =
                        entry.value;
                    break;
                case "termLength":
                    state.selectedProducts[existingProductIndex].term_length =
                        entry.value;
                    break;
                case "termLengthType":
                    state.selectedProducts[
                        existingProductIndex
                    ].term_length_type = entry.value;
                    break;
                case "pds":
                    state.selectedProducts[existingProductIndex].pds =
                        entry.value;
                    break;
                default:
                    console.log("Unknown field:", entry.field);
            }
        },
        [types.UPDATE_SELECTED_PRODUCTS](state, products) {
            products.forEach((product) => {
                const product_id = product.product_id;
                const existingProductIndex = state.selectedProducts.findIndex(
                    (p) => p.product_id === product_id
                );
                if (existingProductIndex === -1) {
                    let entryrow = {
                        product_id: product_id,
                        product_name: "",
                        maximum: "",
                        minimum: "",
                        rate: "",
                        term_length: "",
                        term_length_type: "",
                        pds: "",
                    };
                    state.selectedProducts.push(entryrow);
                } else {
                    state.selectedProducts.splice(existingProductIndex, 1);
                }
            });
        },
        [types.DESELECT_ALL_ACTIVE_PRODUCTS](state, products) {
            console.log(products, "products");
            console.log(state.selectedProductsids, "products");

            // Convert selectedProductsids to a Set for faster lookup
            const selectedProductsSet = new Set(
                state.selectedProducts.map((p) => p.product_id)
            );
            const selectedProductsIdsSet = new Set(state.selectedProductsids);

            let indexes_to_remove = [];

            // Iterate over products and mark indexes for removal
            products.forEach((product) => {
                if (selectedProductsSet.has(product)) {
                    const existingProductIndex =
                        state.selectedProducts.findIndex(
                            (p) => p.product_id === product
                        );
                    indexes_to_remove.push(existingProductIndex);
                }
                if (selectedProductsIdsSet.has(product)) {
                    const existingProductIndex1 =
                        state.selectedProductsids.findIndex(
                            (p) => p === product
                        );
                    indexes_to_remove.push(existingProductIndex1);
                }
            });
            indexes_to_remove = Array.from(new Set(indexes_to_remove)).sort(
                (a, b) => b - a
            );
            indexes_to_remove.forEach((index) =>
                state.selectedProducts.splice(index, 1)
            );

            // Update selectedProductsids
            state.selectedProductsids = state.selectedProductsids.filter(
                (_, index) => !indexes_to_remove.includes(index)
            );

            console.log(state.selectedProducts, "state.selectedProducts");
            console.log(state.selectedProductsids, "state.selectedProductsids");
        },
        [types.UPDATE_SELECTED_PRODUCTS_MANUAL](state, products) {
            products.forEach((product) => {
                const product_id = product.product_id;
                const existingProductIndex = state.selectedProducts.findIndex(
                    (p) => p.product_id === product_id
                );
                if (existingProductIndex === -1) {
                    let entryrow = {
                        product_id: product_id,
                        product_name: "",
                        maximum: "",
                        minimum: "",
                        rate: "",
                        term_length: "",
                        term_length_type: "",
                        pds: "",
                    };
                    state.selectedProducts.push(entryrow);
                }
            });
        },
        [types.SET_ALL_PRODUCTS_SELECTED](state, allproductsselect) {
            state.selectAllProducts = allproductsselect;
        },
        [types.SET_ALL_PRODUCTS_SELECTED](state, allproductsselect) {
            state.selectAllProducts = allproductsselect;
        },
        [types.SET_CAMPAIGN_SELECTED_PRODUCTS](state, products) {
            state.selectedProducts = products;
        },
        [types.SET_CAMPAIGN_SELECTED_PRODUCTS_IDS](state, selectedProductsids) {
            state.selectedProductsids = selectedProductsids;
        },
        [types.UPDATE_CAMPAIGN_SELECTED_PRODUCTS_IDS_MANUAL](
            state,
            selectedProductIds
        ) {
            selectedProductIds.forEach((id) => {
                if (!state.selectedProductsids.includes(id)) {
                    state.selectedProductsids.push(id);
                }
            });
        },
        [types.UPDATE_CAMPAIGN_SELECTED_PRODUCTS_IDS](
            state,
            selectedProductIds
        ) {
            selectedProductIds.forEach((id) => {
                if (!state.selectedProductsids.includes(id)) {
                    state.selectedProductsids.push(id);
                } else {
                    const index = state.selectedProductsids.indexOf(id);
                    state.selectedProductsids.splice(index, 1);
                }
            });
        },
    },
    actions: {},
    getters: {
        getAllActiveProducts(state) {
            return state.activeProductList;
        },
        getSelectAllProducts(state) {
            return state.selectAllProducts;
        },
        getCampaignSelectedProducts(state) {
            return state.selectedProducts;
        },
        getCampaignSelectedProductIDS(state) {
            return state.selectedProductsids;
        },
        getSelectedProductAtribute: (state) => (productt, attributeType) => {
            const productIndex = state.selectedProducts.findIndex(
                (p) => p.product_id === productt
            );
            if (productIndex !== -1) {
                const product = state.selectedProducts[productIndex];

                switch (attributeType) {
                    case "name":
                        return product.product_name;
                    case "rate":
                        return product.rate;
                    case "minimum":
                        return product.minimum;
                    case "maximum":
                        return product.maximum;
                    case "termLength":
                        return product.term_length;
                    case "termLengthType":
                        return product.term_length_type;
                    case "pds":
                        return product.pds;
                    default:
                        console.error("Invalid attribute type:", attributeType);
                        return null;
                }
            } else {
                console.error("Product with ID", product_id, "not found.");
                return null;
            }
        },
    },
};

export default campaignModule;
