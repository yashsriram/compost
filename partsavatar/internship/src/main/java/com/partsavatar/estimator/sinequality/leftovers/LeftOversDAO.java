package com.partsavatar.estimator.sinequality.leftovers;


import com.partsavatar.estimator.sinequality.SInequality;

import java.util.Vector;

public interface LeftOversDAO {

    public Vector<SInequality> getAll();

    public boolean clearTableAndSave(final Vector<SInequality> partEstimates);

}
