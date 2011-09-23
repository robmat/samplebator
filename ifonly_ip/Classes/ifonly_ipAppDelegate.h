#import <UIKit/UIKit.h>
#import "GData.h"

@interface ifonly_ipAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;

+ (NSString*)getTempMovieInfoPath;
+ (GDataServiceGoogleYouTube*) getYTServiceWithcredentials: (BOOL) withCredentials; 
@end

