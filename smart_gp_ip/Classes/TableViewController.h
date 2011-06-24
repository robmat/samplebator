
//Copyright Applicable Ltd 2011

#import <UIKit/UIKit.h>

@interface TableViewController : UITableViewController {
	NSArray* dataArray;
	UINavigationController* navController;
}

@property (nonatomic, retain) NSArray* dataArray;
@property (nonatomic, retain) UINavigationController* navController;

- (void) clipButtonToItsTitleWidth: (UIButton*) btn;

@end
