package com.partsavatar.allocator.estimates;

import com.partsavatar.estimator.productdimension.ProductDimension;
import com.partsavatar.estimator.productdimension.ProductDimensionDAO;
import com.partsavatar.estimator.productdimension.ProductDimensionDAOImpl;
import com.partsavatar.packer.components.Part;
import com.partsavatar.packer.components.Vector3D;

public class Estimate {
    private static ProductDimensionDAO estimator = new ProductDimensionDAOImpl();

    public static Part getEstimateForProduct(String productSku) {
        ProductDimension estimate = estimator.getBySku(productSku);
//        return new Part(
//                estimate.getSku(),
//                new Vector3D(
//                        (int) estimate.getLength(),
//                        (int) estimate.getBreadth(),
//                        (int) estimate.getHeight()
//                ),
//                estimate.getWeight());
        //Temporary Estimation for testing
        return new Part(productSku, new Vector3D(10, 10, 10), 10.);
    }
}
