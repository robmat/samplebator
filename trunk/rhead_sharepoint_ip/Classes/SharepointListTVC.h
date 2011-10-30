#import <UIKit/UIKit.h>
#import "SoapRequest.h"

@interface SharepointListTVC : UITableViewController {
	
	NSMutableDictionary* listsData;
	NSMutableDictionary* datesData;
	NSArray* keysArr;
	UINavigationController* navCntrl;
}

@property (nonatomic, retain) NSMutableDictionary* listsData;
@property (nonatomic, retain) NSMutableDictionary* datesData;
@property (nonatomic, retain) NSArray* keysArr;
@property (nonatomic, retain) UINavigationController* navCntrl;

@end

@interface SharepointListCell : UITableViewCell {
	IBOutlet UILabel* titleLbl;
	IBOutlet UILabel* dateLbl;
}

@property(nonatomic,retain) IBOutlet UILabel* titleLbl;
@property(nonatomic,retain)	IBOutlet UILabel* dateLbl;

@end
