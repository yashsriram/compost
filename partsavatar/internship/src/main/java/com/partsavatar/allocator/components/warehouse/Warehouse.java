package com.partsavatar.allocator.components.warehouse;

import com.partsavatar.allocator.components.AddressInfo;
import lombok.EqualsAndHashCode;
import lombok.Getter;
import lombok.NonNull;
import lombok.ToString;

import java.util.Vector;

/**
 * Assert if warehouse has a product it definitely contains that product's cost price and clone count
 */
@Getter
@EqualsAndHashCode(of = {"id"})
@ToString(of = {"id"})
public class Warehouse {

    @NonNull
    private String id;
    private String satelliteStore;
    @NonNull
    private AddressInfo address;
    
    
    public Warehouse(@NonNull final String id, @NonNull final AddressInfo address, final String satelliteStore) {
        this.id = id;
        this.address = address;
        this.address.initEasyPostAddress(id);
        if(!satelliteStore.equals("NULL"))
        	this.satelliteStore = satelliteStore;
    }

    public static Vector<Warehouse> getAll() {
        return new WarehouseDAOImpl().getAll();
    }

    public double getCostPrice(@NonNull final String sku) {
        return new WarehouseDAOImpl().getCostPrice(this.id, sku);
    }

    public int getCloneCount(@NonNull final String sku) {
        return new WarehouseDAOImpl().getCloneCount(this.id, sku);
    }

    public boolean containsProduct(@NonNull final String sku) {
        return new WarehouseDAOImpl().containsProduct(this.id, sku);
    }
}
