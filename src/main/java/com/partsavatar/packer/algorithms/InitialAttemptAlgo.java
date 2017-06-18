package com.partsavatar.packer.algorithms;


import com.partsavatar.packer.components.Box;
import com.partsavatar.packer.components.Part;
import com.partsavatar.packer.components.Vector3D;
import com.partsavatar.packer.components.WarehouseOrder;

public class InitialAttemptAlgo extends FinalAlgortihmBaseClass {

    private static void fillSectionC(Box b, WarehouseOrder newWarehouseOrder, Vector3D partDim, Vector3D boxDim, Vector3D origLeftBottom, Integer i) {
        Vector3D newBoxDim = new Vector3D(partDim.getX(), boxDim.getY() - partDim.getY(), partDim.getZ());
        Vector3D newLeftBottom = new Vector3D(origLeftBottom.getX(), origLeftBottom.getY() + partDim.getY(), origLeftBottom.getZ());

        b.setDimension(newBoxDim);
        fill(b, newWarehouseOrder, newLeftBottom, i);
        b.setDimension(boxDim);
    }

    private static void fillSectionB(Box b, WarehouseOrder newWarehouseOrder, Vector3D partDim, Vector3D boxDim, Vector3D origLeftBottom, Integer i) {
        Vector3D newBoxDim = new Vector3D(partDim.getX(), boxDim.getY(), boxDim.getZ() - partDim.getZ());
        Vector3D newLeftBottom = new Vector3D(origLeftBottom.getX(), origLeftBottom.getY(), origLeftBottom.getZ() + partDim.getZ());

        b.setDimension(newBoxDim);
        fill(b, newWarehouseOrder, newLeftBottom, i);
        b.setDimension(boxDim);
    }

    private static void fillSectionA(Box b, WarehouseOrder newWarehouseOrder, Vector3D partDim, Vector3D boxDim, Vector3D origLeftBottom, Integer i) {
        Vector3D newBoxDim = new Vector3D(boxDim.getX() - partDim.getX(), boxDim.getY(), boxDim.getZ());
        Vector3D newLeftBottom = new Vector3D(origLeftBottom.getX() + partDim.getX(), origLeftBottom.getY(), origLeftBottom.getZ());

        b.setDimension(newBoxDim);
        fill(b, newWarehouseOrder, newLeftBottom, i);
        b.setDimension(boxDim);
    }

    private static void fill(Box b, WarehouseOrder ord, Vector3D leftBottom, Integer i) {
        // check for parts in order. check next while qty = 0 or that part is bigger than box size
        while (ord.getOrderList().size() > i && (ord.getOrderList().get(i).getQuantity() == 0 ||
                !b.getDimension().rotateAndCheckIsEqualOrGreater(ord.getOrderList().get(i)))) {
            i++;
        }
        if (i == ord.getOrderList().size())//No other item in order fits the box
            return;
        else {//update order, box, left bottom for section c
            Integer currQty = ord.getOrderList().get(i).getQuantity();
            ord.getOrderList().get(i).setQuantity(currQty - 1);

            Part p = ord.getOrderList().get(i).copy();
            p.setPosition(leftBottom);
            p.setQuantity(1);
            b.addPart(p);

            fillSectionC(b, ord, p.getDimension(), b.getDimension(), leftBottom, i);
            fillSectionB(b, ord, p.getDimension(), b.getDimension(), leftBottom, i);
            fillSectionA(b, ord, p.getDimension(), b.getDimension(), leftBottom, i);
        }

    }

    static WarehouseOrder MainAlgo(Box b, WarehouseOrder newWarehouseOrder) {
        fill(b, newWarehouseOrder, new Vector3D(0, 0, 0), 0);
        return newWarehouseOrder;
    }
}
