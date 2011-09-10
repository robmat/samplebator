#import <UIKit/UIKit.h>
#import "SoapRequest.h"

@interface SharepointListsTVC : UITableViewController <SoapRequestDelegate> {
	NSMutableDictionary* listsData;
	NSArray* keysArr;
	NSMutableDictionary* listTitleToImageNameMap;
	/*NSMutableDictionary* listTitleToUrlAttributeName;*/
	NSString* selectedRowTitle;
	UINavigationController* navCntrl;
}

@property (nonatomic, retain) NSMutableDictionary* listsData;
@property (nonatomic, retain) NSArray* keysArr;
@property (nonatomic, retain) NSString* selectedRowTitle;
@property (nonatomic, retain) UINavigationController* navCntrl;

@end
