#import <UIKit/UIKit.h>
#import "SoapRequest.h"

@interface SharepointListTVC : UITableViewController <SoapRequestDelegate> {
	
	NSMutableDictionary* listsData;
	NSArray* keysArr;
	UINavigationController* navCntrl;
	NSString* myListName;
	UIViewController* delegate;
	NSIndexPath* indexPressed;
	NSString* currentFolder;
}

@property (nonatomic, retain) NSMutableDictionary* listsData;
@property (nonatomic, retain) NSArray* keysArr;
@property (nonatomic, retain) UINavigationController* navCntrl;
@property (nonatomic, retain) NSString* myListName;
@property (nonatomic, retain) UIViewController* delegate;
@property (nonatomic, retain) NSString* currentFolder;

- (BOOL)str: (NSString*) str contains: (NSString*) con;

@end

@interface SharepointListCell : UITableViewCell {
	IBOutlet UILabel* titleLbl;
	IBOutlet UILabel* dateLbl;
	IBOutlet UIImageView* icon;
}

@property(nonatomic,retain) IBOutlet UILabel* titleLbl;
@property(nonatomic,retain)	IBOutlet UILabel* dateLbl;
@property(nonatomic,retain)	IBOutlet UIImageView* icon;

@end
