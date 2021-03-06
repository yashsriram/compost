package com.partsavatar.allocator.allocationtypes;

import com.partsavatar.allocator.api.google.Response;
import com.partsavatar.allocator.components.CustomerOrder;
import com.partsavatar.allocator.components.warehouse.Warehouse;
import com.partsavatar.allocator.exceptions.OrderCannotBeFullfilledException;
import com.partsavatar.allocator.operations.Pipe;
import lombok.NonNull;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class OptimizeShippingDuration {

    public static Map<Warehouse, Map<String, Integer>> allocate(@NonNull final CustomerOrder customerOrder, @NonNull final Map<Response, Warehouse> response_warehouse_map) throws OrderCannotBeFullfilledException {
        // Get all responses
        ArrayList<Response> all_responses = new ArrayList<>();
        for (Map.Entry<Response, Warehouse> response_warehouse_pair : response_warehouse_map.entrySet()) {
            all_responses.add(response_warehouse_pair.getKey());
        }

        // Sort all responses wrt distance, in turn sorting the warehouses
        all_responses.sort(Response::compareDuration);

        // Answer
        Map<Warehouse, Map<String, Integer>> allocation = new HashMap<>();

        // Make a copy of the customerOrder
        CustomerOrder order = new CustomerOrder(customerOrder);
        // Pipe through the sorted warehouses greedily
        for (Response response : all_responses) {
            Warehouse warehouse = response_warehouse_map.get(response);

            Pipe.PipedOrder pipedOrder = Pipe.pipeOrderGreedily(warehouse, order);
            Map<String, Integer> order_taken = pipedOrder.getOrderTaken();
            order = pipedOrder.getOrderRemaining();
            allocation.put(warehouse, order_taken);
        }

        // CustomerOrder not fulfilled
        if (!order.getProductCloneCountMap().isEmpty()) {
            throw new OrderCannotBeFullfilledException();
        }
        // CustomerOrder fulfilled
        else {
            return allocation;
        }
    }

}
