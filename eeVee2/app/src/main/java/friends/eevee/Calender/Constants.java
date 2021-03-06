package friends.eevee.Calender;

/**
 * Contains constants related to<br>
 * Time conversions,<br>
 * Date, Time, DateTime Classes<br>
 * Gregorian Calender<br>
 *
 * @author pandu
 * @version 1.0
 * */
public class Constants {

    public static final String SPACE_SEP = " ";

    public static final int MINUTES_IN_HOUR = 60;
    public static final int MINUTES_IN_DAY = 1440;
    public static final int SECONDS_IN_MINUTE = 60;
    public static final int SECONDS_IN_HOUR = 3600;
    public static final int SECONDS_IN_DAY = 86400;
    public static final int HOURS_IN_DAY = 24;

    public static final int DAYS_IN_WEEK = 7;
    public static final int DAYS_IN_STD_MONTH = 30;
    public static final int DAYS_IN_STD_YEAR = 365;
    public static final int[] STD_MONTH_EXTRAS_ARRAY = {1, -1, 0, 0, 1, 1, 2, 3, 3, 4, 4, 5};

    public static final String[] MONTH_NAMES = {"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"};
    public static final String[] SUFFIXES = {"st", "nd", "rd", "th"};
    public static final int[] DAYS_IN_MONTH_NLY = {31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31};
    public static final int[] DAYS_IN_MONTH_LY = {31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31};

}
