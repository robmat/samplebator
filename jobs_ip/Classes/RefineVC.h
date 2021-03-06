#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "CXMLDocument.h"
#import "ASIHTTPRequestDelegate.h"

@interface RefineVC : VCBase <UITableViewDelegate, UITableViewDataSource, ASIHTTPRequestDelegate> {
	
	IBOutlet UISearchBar* keywordSearchBar;
	IBOutlet UISearchBar* locationSearchBar;
	CXMLDocument* doc;
	IBOutlet UITableView* tableView;
	IBOutlet UIPickerView* pickerView;
	NSMutableDictionary* refineParams;
	NSString* selectedFieldName;
	CXMLNode* selectedFieldNode;
}

@property (nonatomic,retain) IBOutlet UISearchBar* keywordSearchBar;
@property (nonatomic,retain) IBOutlet UISearchBar* locationSearchBar;
@property (nonatomic,retain) CXMLDocument* doc;
@property (nonatomic,retain) IBOutlet UITableView* tableView;
@property (nonatomic,retain) IBOutlet UIPickerView* pickerView;
@property (nonatomic,retain) NSString* selectedFieldName;
@property (nonatomic,retain) CXMLNode* selectedFieldNode;

- (IBAction)searchAction: (id) sender;

@end
