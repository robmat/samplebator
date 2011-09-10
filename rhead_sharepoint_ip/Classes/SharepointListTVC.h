#import <UIKit/UIKit.h>
#import "SoapRequest.h"

@interface SharepointListTVC : UITableViewController <SoapRequestDelegate> {
	
	NSMutableDictionary* listsData;
	NSArray* keysArr;
	UINavigationController* navCntrl;
}

@property (nonatomic, retain) NSMutableDictionary* listsData;
@property (nonatomic, retain) NSArray* keysArr;
@property (nonatomic, retain) UINavigationController* navCntrl;

@end
