#import <UIKit/UIKit.h>

@interface ifonly_ipAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;

+ (NSString*)getTempMovieInfoPath;

@end

