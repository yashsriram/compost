package com.partsavatar.allocator.allocationtypes;

import com.partsavatar.allocator.components.CustomerOrder;
import com.partsavatar.allocator.components.warehouse.Warehouse;
import com.partsavatar.allocator.exceptions.OrderCannotBeFullfilledException;
import lombok.NonNull;

import java.util.Comparator;
import java.util.HashMap;
import java.util.Map;
import java.util.Vector;

public class OptimizeCostPrice {

    public static Map<Warehouse, Map<String, Integer>> allocate(@NonNull final CustomerOrder customerOrder, @NonNull final Vector<Warehouse> warehouses) throws OrderCannotBeFullfilledException {
        // Answer
        Map<Warehouse, Map<String, Integer>> allocation = new HashMap<>();

        // Make a copy of the customerOrder
        CustomerOrder cost_price_copy = new CustomerOrder(customerOrder);

        // Initialize customerOrder in all warehouses
        for (Warehouse warehouse : warehouses) {
            allocation.put(warehouse, new HashMap<>());
        }

        // For each ordered part
        for (Map.Entry<String, Integer> ordered_part : cost_price_copy.getProductCloneCountMap().entrySet()) {
            String part_id = ordered_part.getKey();

            // Sort warehouses according to cost price of this part
            warehouses.sort(new Comparator<Warehouse>() {
                /**
                 * Sort all warehouses wrt cost price of the ordered part
                 * A warehouse containing part < A warehouse NOT containing part
                 * If both warehouses does not contain the part they are equal
                 */
                @Override
                public int compare(Warehouse w1, Warehouse w2) {

                    // If both warehouses do not contain
                    if (!w1.containsProduct(part_id) &&
                            !w2.containsProduct(part_id)) {
                        // w1 == w2
                        return 0;
                    }
                    // w1 contains the part and w2 does not contain
                    else if (w1.containsProduct(part_id) &&
                            !w2.containsProduct(part_id)) {
                        // w1 < w2 -> return -1
                        return -1;
                    }
                    // w2 contains the part and w1 does not contain
                    else if (!w1.containsProduct(part_id) &&
                            w2.containsProduct(part_id)) {
                        // w1 > w2 -> return 1
                        return 1;
                    }
                    // Both contain the part
                    else {
                        double w1_cost_price = w1.getProductInfo(part_id).getCostPrice();
                        double w2_cost_price = w2.getProductInfo(part_id).getCostPrice();

                        if (w1_cost_price < w2_cost_price) return -1;
                        else if (w1_cost_price == w2_cost_price) return 0;
                        else return 1;
                    }

                }
            });

            // Pipe the part through the sorted warehouses greedily
            for (Warehouse warehouse : warehouses) {
                int part_order_taken = warehouse.pipeProductGreedily(cost_price_copy, part_id);
                if (part_order_taken != 0) {
                    Map<String, Integer> wh_allocation = allocation.get(warehouse);
                    // As all parts in an customerOrder are unique
                    // wh_allocation will not previously have a key == part_id
                    wh_allocation.put(part_id, part_order_taken);
                }
            }

            // If still this part remains in the cost_price_copy -> customerOrder cannot be fulfilled
            if (cost_price_copy.getProductCloneCountMap().containsKey(part_id)) {
                throw new OrderCannotBeFullfilledException();
            }
        }

        // At this point customerOrder can definitely be fulfilled
        return allocation;
    }

}
