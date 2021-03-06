package friends.eevee.Activities.TimeWall.Utilities;

import friends.eevee.Calender.Date;
import friends.eevee.Calender.Time;

public class UIPreferences {

    public static int PAST_TIME = 0;                         /** minutes*/
    public final static int MINIMUM_PAST_TIME = 0;                  /** minutes step size 60 minutes*/
    public final static int MAXIMUM_PAST_TIME = 1440;               /** minutes */ // one week
    public final static int PAST_TIME_STEP = 60;                     /** minutes */ // one week

    /**
     * changes
     * time divisions
     * event stubs
     * */
    public static float MINUTE_PX_SCALE = 2.0f;                 /** 1 MINUTE = (this many) px */
    public final static float MIN_MINUTE_PX_SCALE = 1.0f;             /** 1 MINUTE = (this many) px */
    public final static float MAX_MINUTE_PX_SCALE = 6.0f;             /** 1 MINUTE = (this many) px */
    public final static float MINUTE_PX_SCALE_STEP = 0.5f;             /** 1 MINUTE = (this many) px */

    public static Time START_OF_THE_DAY = new Time("05 00 00"," ");

    public static Date SHOWING_DATE = new Date(true);

    public static class TIME_DIVISIONS{

        /**
         * changes
         * time divisions
         * */
        public static int MINUTES_BW_DIVISIONS = 60;           /** minutes*/
        public final static int MIN_MINUTES_BW_DIVISIONS = 30;       /** minutes*/
        public final static int MAX_MINUTES_BW_DIVISIONS = 60;       /** minutes*/
        public final static int MINUTES_BW_DIVISIONS_STEP = 30;       /** minutes*/

        /**
         * changes
         * time divisions
         * */
        public static int TIME_TEXT_SIZE = 36;              /** px*/
        public final static int MIN_TEXT_SIZE = 0;               /** px*/
        public final static int MAX_TEXT_SIZE = 100;              /** px*/
        public final static int TEXT_SIZE_STEP = 2;              /** px*/

        public final static int DIV_MARK_WIDTH = 1;     /** px */
        public final static int PRESENT_MARK_WIDTH = 5; /** px */
    }

    public static class EVENT_STUB{
        public static int ANGLE_OF_TEXT = 90;       /** degrees clockwise */
        public static int EVENT_WIDTH = 100;         /** px */
    }

    public static void setDeviceDependentValues(int WIDTH , int HEIGHT){
        GESTURES.GESTURE_AREA_MIN = HEIGHT / 20;
        GESTURES.GESTURE_AREA_MAX = HEIGHT / 4;
        EVENT_STUB.EVENT_WIDTH = WIDTH/4;
    }

    public static class GESTURES{
        public static float FINGER_STROKE_WIDTH = 25;           // px
        public static String FINGER_TOUCH_COLOR = "#FFFFFF";    // px

        public static int GESTURE_AREA_MIN ;                 // px
        public static int GESTURE_AREA_MAX ;                 // px
    }

}
